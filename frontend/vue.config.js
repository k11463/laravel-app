module.exports = {
  "transpileDependencies": [
    "vuetify"
  ],
  publicPath: './',
  devServer: {
    proxy: "http://localhost:8000"
  }
}