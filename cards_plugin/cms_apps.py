from cms.app_base import CMSApp
from cms.apphook_pool import apphook_pool
from django.utils.translation import ugettext_lazy as _


class CardsApphook(CMSApp):
    app_name = "cards"
    name = _("Cards Application")

    def get_urls(self, page=None, language=None, **kwargs):
        return ["cards.urls"]


apphook_pool.register(CardsApphook)  # register the application