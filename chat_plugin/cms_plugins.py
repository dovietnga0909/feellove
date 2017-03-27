from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from chat_plugin.models import RoomPluginModel
from django.utils.translation import ugettext as _


class ChatPluginPublisher(CMSPluginBase):
    model = RoomPluginModel # model where plugin data are saved
    module = _("Chat")
    name = _("Chat Plugin")  # name of the plugin in the interface
    render_template = "chat/chat_room.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context

plugin_pool.register_plugin(ChatPluginPublisher)  # register the plugin