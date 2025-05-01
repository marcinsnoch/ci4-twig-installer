export const PluginsDir = "public/plugins/";
export const Plugins = [
    {
        from: "node_modules/bootstrap/dist",
        toPluginsDir: "bootstrap"
    },
    {
        from: "node_modules/bootstrap-icons/font/",
        toPluginsDir: "bootstrap-icons/"
    },
    {
        from: "node_modules/bootstrap-table/dist",
        toPluginsDir: "bootstrap-table"
    },
    {
        from: "node_modules/jquery/dist",
        toPluginsDir: "jquery"
    },
    {
        from: "node_modules/toastr/build",
        toPluginsDir: "toastr"
    }
];
