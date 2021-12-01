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

        this.loaditems();

        blockdiv.addEventListener("scroll", function(event) {
            var scrollTop = event.target.scrollTop;
            var scrollHeight = event.target.scrollHeight;
            var offsetHeight = event.target.offsetHeight;

            var hasmoreitems = event.target.dataset.hasmoreitems === 'true';

            if(scrollTop + offsetHeight > scrollHeight - 40) {
                if (!hasmoreitems) {
                    return;
                }

                if (!this.wait) {
                    this.loaditems();
                }
            }
        }.bind(this), false);
    };

    EvokeFeed.prototype.wait = false;

    EvokeFeed.prototype.loaditems = function() {
        this.wait = true;

        var containerelement = document.querySelector('.block-evokefeed');

        var courseid = parseInt(containerelement.dataset.courseid);
        var limitcomments = parseInt(containerelement.dataset.limitcomments);
        var limitlikes = parseInt(containerelement.dataset.limitlikes);
        var limitskilpoints = parseInt(containerelement.dataset.limitskilpoints);
        var limitevocoins = parseInt(containerelement.dataset.limitevocoins);
        var limitbadges = parseInt(containerelement.dataset.limitbadges);
        var hasmoreitems = containerelement.dataset.hasmoreitems === 'true';

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
            var loadingdiv = document.querySelector('.block_evokefeed .loading-placeholder');

            loadingdiv.classList.add('hidden');

            var data = JSON.parse(response.data);

            containerelement.dataset.limitcomments = limitcomments + 1;
            containerelement.dataset.limitlikes = limitlikes + 1;
            containerelement.dataset.limitskilpoints = limitskilpoints + 1;
            containerelement.dataset.limitevocoins = limitevocoins + 1;
            containerelement.dataset.limitbadges = limitbadges + 1;
            containerelement.dataset.hasmoreitems = data.hasmoreitems;

            var ulelement = document.querySelector('.block-evokefeed .list-group');

            data.items.forEach(function(element) {
                var url = element.url;
                url = url.replace(/&amp;/g, "&");

                var listitem = document.createElement('a');
                listitem.className = 'list-group-item list-group-item-action fadein';
                listitem.href = url;
                listitem.innerHTML = '<i class="fa '+element.icon+'"></i> ' + element.text;

                ulelement.appendChild(listitem);
            });

            this.wait = false;
        }.bind(this));
    }

    return {
        'init': function() {
            return new EvokeFeed();
        }
    };
});
