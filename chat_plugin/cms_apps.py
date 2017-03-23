from cms.app_base import CMSApp
from cms.apphook_pool import apphook_pool
from django.utils.translation import ugettext_lazy as _


class ChatApphook(CMSApp):
    app_name = "chat"
    name = _("Chat Application")

    def get_urls(self, page=None, language=None, **kwargs):
        return ["chat.urls"]


apphook_pool.register(ChatApphook)  # register the application