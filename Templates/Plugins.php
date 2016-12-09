<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <title>设置</title>
        <?php require_once 'Header.php' ?>
    </head>
    <body>
        <?php require_once 'Sidebar.php' ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12" style="margin-top: 10px">
                    <div class="row">
                        <?php
                            $pluginModel = new PluginModel;
                            $pluginList = $pluginModel->scan ();
                            $pluginEnabledList = $pluginModel->getinfo ();
                            foreach ($pluginList as $pluginList_d) {
                                $pluginInfo_f = $pluginModel->getinfo_f ($pluginList_d);
                                ?>
                                    <div class="col-xs-12 col-lg-6">
                                        <div class="card card-block">
                                            <h4 class="card-title">
                                                <?php
                                                    if (!isset ($pluginInfo_f['PluginName'])) {
                                                        $pluginInfo_f['PluginName'] = '没有名称';
                                                    }
                                                    if (isset ($pluginInfo_f['PluginURL'])) {
                                                        echo '<a target="_blank" href="' . $pluginInfo_f['PluginURL'] . '">' . $pluginInfo_f['PluginName'] . '</a>';
                                                    } else {
                                                        echo $pluginInfo_f['PluginName'];
                                                    }
                                                    
                                                    echo ' - ';
                                                    echo $pluginList_d;
                                                ?>
                                            </h4>
                                            <p class="card-text">
                                                <?php
                                                    if (!isset ($pluginInfo_f['Description'])) {
                                                        $pluginInfo_f['Description'] = '没有描述';
                                                    }
                                                    echo $pluginInfo_f['Description'];
                                                ?>
                                            </p>
                                            <div style="float: right">
                                                <?php
                                                    if (!isset ($pluginInfo_f['Author'])) {
                                                        $pluginInfo_f['Author'] = '没有作者';
                                                    }
                                                    if (isset ($pluginInfo_f['AuthorURL'])) {
                                                        echo '<a target="_blank" href="' . $pluginInfo_f['AuthorURL'] . '">' . $pluginInfo_f['Author'] . '</a>';
                                                    } else {
                                                        echo $pluginInfo_f['Author'];
                                                    }
                                                ?>
                                                <?php
                                                    $pluginEnabledSub = NULL;
                                                    foreach ($pluginEnabledList as $pluginEnabledList_i => $pluginEnabledList_d) {
                                                        if ($pluginEnabledList_d['pcn'] == $pluginList_d) {
                                                            $pluginEnabledSub = $pluginEnabledList_i;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    if ($pluginEnabledSub === NULL) {
                                                        ?>
                                                            <button id="install" data-pcn="<?php echo $pluginList_d ?>" type="button" class="btn btn-success">安装插件</button>
                                                        <?php
                                                    } else {
                                                        if ($pluginEnabledList[$pluginEnabledSub]['enabled'] == 0) {
                                                            ?>
                                                                <button id="enable" data-pcn="<?php echo $pluginList_d ?>" type="button" class="btn btn-info">启用插件</button>
                                                                <button id="uninstall" data-pcn="<?php echo $pluginList_d ?>" type="button" class="btn btn-danger">卸载插件</button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                                <button id="disable" data-pcn="<?php echo $pluginList_d ?>" type="button" class="btn btn-warning">禁用插件</button>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                                <a href="<?php echo APP_URL ?>/index.php/PluginMain/edit/<?php echo $pluginList_d ?>" class="btn btn-primary">编辑插件</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'Footer.php' ?>
        <script>
            $("button").click(function(){
                buttonThis = $(this);
                $(buttonThis).attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo APP_URL ?>/index.php/plugins/" + $(buttonThis).attr("id"),
                    data: {
                        "pcn": $(buttonThis).data("pcn")
                    },
                    success: function(data, textStatus, jqXHR){
                        if(data.code == '0'){
                            location.reload();
                        }else{
                            textOld = $(buttonThis).text();
                            $(buttonThis).text(data.msg);
                            setTimeout(function(){
                                $(buttonThis).text(textOld);
                                $(buttonThis).removeAttr("disabled");
                            }, 2000);
                        }
                    },
                    dataType: "json"
                });
            })
        </script>
    </body>
</html>