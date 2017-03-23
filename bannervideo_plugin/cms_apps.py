from cms.app_base import CMSApp
from cms.apphook_pool import apphook_pool
from django.utils.translation import ugettext_lazy as _


class BannervideoApphook(CMSApp):
    app_name = "bannervideo"
    name = _("Banner Video Application")
    def get_urls(self, page=None, language=None, **kwargs):
        return ["bannervideo.urls"]

apphook_pool.register(BannervideoApphook)