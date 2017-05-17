from cms.app_base import CMSApp
from cms.apphook_pool import apphook_pool
from django.utils.translation import ugettext_lazy as _


class RecordApphook(CMSApp):
    app_name = "record"
    name = _("Record Application")

    def get_urls(self, page=None, language=None, **kwargs):
        return ["record.urls"]


apphook_pool.register(RecordApphook)  # register the application