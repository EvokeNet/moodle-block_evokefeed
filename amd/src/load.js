/**
 * Add comment js logic.
 *
 * @package    mod_evokeportfolio
 * @copyright  2021 World Bank Group <https://worldbank.org>
 * @author     Willian Mano <willianmanoaraujo@gmail.com>
 */

/* eslint-disable */
define(['core/ajax'], function(Ajax) {
    var EvokeFeed = function() {
        var blockdiv = document.querySelector('.block-evokefeed');

        blockdiv.addEventListener("scroll", function(event) {
            var scrollTop = event.target.scrollTop;
            var scrollHeight = event.target.scrollHeight;
            var offsetHeight = event.target.offsetHeight;

            var courseid = event.target.dataset.courseid;
            var limitcomments = event.target.dataset.limitcomments;
            var limitlikes = event.target.dataset.limitlikes;
            var limitskilpoints = event.target.dataset.limitskilpoints;
            var limitevocoins = event.target.dataset.limitevocoins;
            var limitbadges = event.target.dataset.limitbadges;
            var hasmoreitems = event.target.dataset.hasmoreitems === 'true';

            if(scrollTop + offsetHeight > scrollHeight - 5){
                if (!hasmoreitems) {
                    return;
                }

                var request = Ajax.call([{
                    methodname: 'block_evokefeed_load',
                    args: {
                        courseid: courseid,
                        limitcomments: limitcomments,
                        limitlikes: limitlikes,
                        limitskilpoints: limitskilpoints,
                        limitevocoins: limitevocoins,
                        limitbadges: limitbadges,
                        hasmoreitems: hasmoreitems
                    }
                }]);

                request[0].done(function(response) {
                    var data = JSON.parse(response.data);

                    event.target.dataset.limitcomments = data.limitcomments;
                    event.target.dataset.limitlikes = data.limitlikes;
                    event.target.dataset.limitskilpoints = data.limitskilpoints;
                    event.target.dataset.limitevocoins = data.limitevocoins;
                    event.target.dataset.limitbadges = data.limitbadges;
                    event.target.dataset.hasmoreitems = data.hasmoreitems;

                    var ulelement = document.querySelector('.block-evokefeed .list-group');

                    data.items.forEach(function(element) {
                        var listitem = document.createElement('a');
                        listitem.className = 'list-group-item list-group-item-action fadein';
                        listitem.href = element.url;
                        listitem.innerHTML = '<i class="fa '+element.icon+'"></i> ' + element.text;

                        ulelement.appendChild(listitem);
                    });
                }.bind(this))
            }
        }, false);
    };

    return {
        'init': function() {
            return new EvokeFeed();
        }
    };
});
