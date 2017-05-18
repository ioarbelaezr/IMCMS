tinymce.PluginManager.add("filemanager", function(e) {
    function t(t,i,a,s){
        var w = $(window).innerWidth() - 30,
            h = $(window).innerHeight() - 60;
        if (w > 1800 && (w = 1800), h > 1200 && (h = 1200), w > 600) {
            var d = (w - 20) % 138;
            w = w - d + 10;
        }
        //crear la ventana
        tinyMCE.activeEditor.windowManager.open({
            file : '/fm/ventana.php?tipo='+a,
            title : e.settings.filemanager_title,
            width : w,  
            height : h,
            resizable : "yes",
            inline : "yes",  
            close_previous : "no"
        }, {
            window : s,
            input : t
        });
        return!1;
    }
    return tinymce.activeEditor.settings.file_browser_callback = t, !1;
});