from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from record_plugin.models import RecordPluginModel
from django.utils.translation import ugettext as _


class RecordPluginPublisher(CMSPluginBase):
    model = RecordPluginModel  # model where plugin data are saved
    module = _("Record")
    name = _("Record Plugin")  # name of the plugin in the interface
    render_template = "record/index.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context

plugin_pool.register_plugin(RecordPluginPublisher)  # register the plugin