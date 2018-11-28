// vue-webpack配置 
module.exports = {
  outputDir: "./public/static/",
  baseUrl: process.env.baseUrl,
  pages: {
    index: {
      entry: "src/index.js",
      filename: process.env.htmlPath + "index.html"
    },
    admins: {
      entry: "src/admins.js",
      filename: process.env.htmlPath + "admins.html"
    }
  },
  devServer: {
    port: 88,
    open: true,
    proxy: "http://127.0.0.1:8000"
  },
  chainWebpack: config => {
    config.plugin("copy").tap(args => {
      return [
        [
          {
            from: "src/public"
          }
        ]
      ];
    });
  }
};