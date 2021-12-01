/**
 * Add comment js logic.
 *
 * @package    mod_evokeportfolio
 * @copyright  2021 World Bank Group <https://worldbank.org>
 * @author     Willian Mano <willianmanoaraujo@gmail.com>
 */

/* eslint-disable */
define(['jquery', 'core/ajax'], function($, Ajax) {
    var EvokeFeed = function() {
        var blockdiv = document.querySelector('.block-evokefeed');

        blockdiv.addEventListener("scroll", function(event) {
            var scrollTop = event.target.scrollTop;
            var scrollHeight = event.target.scrollHeight;
            var offsetHeight = event.target.offsetHeight;

            if(scrollTop + offsetHeight > scrollHeight - 5){
                console.log('load more');
            }
        }, false);
    };

    return {
        'init': function() {
            return new EvokeFeed();
        }
    };
});
