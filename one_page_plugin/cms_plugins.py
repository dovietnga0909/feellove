from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from one_page_plugin.models import OnePagePluginModel
from django.utils.translation import ugettext as _


class OnePagePluginPublisher(CMSPluginBase):
    model = OnePagePluginModel  # model where plugin data are saved
    module = _("About Us")
    name = _("About Us")  # name of the plugin in the interface
    render_template = "aboutus/about-us.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context

plugin_pool.register_plugin(OnePagePluginPublisher)  # register the plugin