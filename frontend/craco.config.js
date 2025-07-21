module.exports = {
  webpack: {
    configure: (webpackConfig, { env }) => {
      // Disable CSS minimization in production to avoid forward slash issues
      if (env === 'production') {
        // Remove CSS minimizer
        webpackConfig.optimization.minimizer = webpackConfig.optimization.minimizer.filter(
          minimizer => minimizer.constructor.name !== 'CssMinimizerPlugin'
        );
      }
      return webpackConfig;
    }
  }
};