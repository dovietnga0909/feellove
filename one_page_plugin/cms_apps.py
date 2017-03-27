from cms.app_base import CMSApp
from cms.apphook_pool import apphook_pool
from django.utils.translation import ugettext_lazy as _


class OnePageApphook(CMSApp):
    app_name = "aboutus"
    name = _("One Page Application")
    def get_urls(self, page=None, language=None, **kwargs):
        return ["aboutus.urls"]

apphook_pool.register(OnePageApphook)