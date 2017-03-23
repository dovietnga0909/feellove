from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from cards_plugin.models import CardsPluginModel
from django.utils.translation import ugettext as _


class CardsPluginPublisher(CMSPluginBase):
    model = CardsPluginModel  # model where plugin data are saved
    module = _("Cards")
    name = _("Card Plugin")  # name of the plugin in the interface
    render_template = "ecardcanvas/editor.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context

plugin_pool.register_plugin(CardsPluginPublisher)  # register the plugin