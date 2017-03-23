from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from bannervideo_plugin.models import BannervideoPluginModel
from django.utils.translation import ugettext as _


class BannervideoPluginPublisher(CMSPluginBase):
    model = BannervideoPluginModel  # model where plugin data are saved
    module = _("Banner Video")
    name = _("Banner Video Plugin")  # name of the plugin in the interface
    render_template = "bannervideo/banner_video_plugin.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context

plugin_pool.register_plugin(BannervideoPluginPublisher)  # register the plugin