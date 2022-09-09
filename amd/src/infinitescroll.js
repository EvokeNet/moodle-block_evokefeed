/**
 * Add comment js logic.
 *
 * @package
 * @subpackage mod_evokeportfolio
 * @copyright  2021 World Bank Group <https://worldbank.org>
 * @author     Willian Mano <willianmanoaraujo@gmail.com>
 */
/* eslint-disable */
define(['jquery', 'core/ajax', 'core/templates'], function($, Ajax, Templates) {
    var InfiniteScroll = function(courseid, type) {
        this.courseid = courseid;

        this.type = type;

        this.targetdiv = '#' + type;

        this.controlbutton = document.getElementById('tab-' + type);

        this.loadItems();

        $('.drawercontent').scroll(function(event) {
            var scrollTop = event.target.scrollTop;
            var scrollHeight = event.target.scrollHeight;
            var offsetHeight = event.target.offsetHeight;

            if (this.hasmoreitems && !this.wait && (scrollTop + offsetHeight > scrollHeight - 100)) {
                if (!this.hasmoreitems) {
                    return;
                }

                if (!this.wait) {
                    this.loadItems();
                }
            }
        }.bind(this));

        $('.nav-pills .nav-link').click(function(event) {
            this.controlbutton = event.target;

            this.type = event.target.dataset.type;

            this.hasmoreitems = event.target.dataset.hasmoreitems === 'true';

            this.targetdiv = event.target.dataset.target;

            this.loadItems();
        }.bind(this));
    }

    InfiniteScroll.prototype.loadItems = function() {
        this.wait = true;

        const courseid = parseInt(this.controlbutton.dataset.courseid);
        const limitcomments = parseInt(this.controlbutton.dataset.limitcomments);
        const limitlikes = parseInt(this.controlbutton.dataset.limitlikes);
        const limitskilpoints = parseInt(this.controlbutton.dataset.limitskilpoints);
        const limitevocoins = parseInt(this.controlbutton.dataset.limitevocoins);
        const limitbadges = parseInt(this.controlbutton.dataset.limitbadges);
        const hasmoreitems = this.controlbutton.dataset.hasmoreitems === 'true';

        const request = Ajax.call([{
            methodname: 'block_evokefeed_load',
            args: {
                courseid: courseid,
                type: this.type,
                limitcomments: limitcomments,
                limitlikes: limitlikes,
                limitskilpoints: limitskilpoints,
                limitevocoins: limitevocoins,
                limitbadges: limitbadges,
                hasmoreitems: hasmoreitems
            }
        }]);

        request[0].done(function(response) {
            const data = JSON.parse(response.data);

            this.controlbutton.dataset.limitcomments = limitcomments + 1;
            this.controlbutton.dataset.limitlikes = limitlikes + 1;
            this.controlbutton.dataset.limitskilpoints = limitskilpoints + 1;
            this.controlbutton.dataset.limitevocoins = limitevocoins + 1;
            this.controlbutton.dataset.limitbadges = limitbadges + 1;
            this.controlbutton.dataset.hasmoreitems = data.hasmoreitems;

            this.hasmoreitems = data.hasmoreitems;

            Templates.render('block_evokefeed/timeline-item', data).then(function(content) {
                const targetdiv = $(this.targetdiv);

                targetdiv.find('.loading-placeholder').addClass('hidden');

                targetdiv.find('.timeline').append(content);

                this.wait = false;
            }.bind(this));

            this.wait = false;
        }.bind(this));
    };

    InfiniteScroll.prototype.wait = false;

    InfiniteScroll.prototype.courseid = 0;

    InfiniteScroll.prototype.type = 'team';

    InfiniteScroll.prototype.targetdiv = '#team';

    InfiniteScroll.prototype.controlbutton = null;

    InfiniteScroll.prototype.hasmoreitems = true;

    return {
        'init': function(courseid, type) {
            return new InfiniteScroll(courseid, type);
        }
    };
});
