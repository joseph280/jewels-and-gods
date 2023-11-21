const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@Icons': path.resolve('resources/js/Components/Common/Icons'),
        },
    },
    output: {
        chunkFilename: 'js/[name].js?id=[chunkhash]',
    },
};
