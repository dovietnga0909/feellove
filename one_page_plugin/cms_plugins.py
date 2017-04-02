from cms.plugin_base import CMSPluginBase
from cms.plugin_pool import plugin_pool
from one_page_plugin.models import OnePagePluginModel
from one_page_plugin.models import ContactPluginModel
from one_page_plugin.models import PartnerPluginModel
from one_page_plugin.models import ServicePluginModel
from django.utils.translation import ugettext as _


class OnePagePluginPublisher(CMSPluginBase):
    model = OnePagePluginModel  # model where plugin data are saved
    module = _("About Us")
    name = _("About Us")  # name of the plugin in the interface
    render_template = "aboutus/about-us.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context
class ContactPluginPublisher(CMSPluginBase):
    model = ContactPluginModel # model where plugin data are saved
    module = _("Contact")
    name = _("Contact")  # name of the plugin in the interface
    render_template = "contact/contact.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context


class PartnerPluginPublisher(CMSPluginBase):
    model = PartnerPluginModel  # model where plugin data are saved
    module = _("Partner")
    name = _("Partner")  # name of the plugin in the interface
    render_template = "partner/partner.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context


class ServicePluginPublisher(CMSPluginBase):
    model = ServicePluginModel  # model where plugin data are saved
    module = _("Service")
    name = _("Service")  # name of the plugin in the interface
    render_template = "service/service.html"

    def render(self, context, instance, placeholder):
        context.update({'instance': instance})
        return context
plugin_pool.register_plugin(ContactPluginPublisher)  # register the plugin
plugin_pool.register_plugin(ServicePluginPublisher)  # register the plugin
plugin_pool.register_plugin(PartnerPluginPublisher)  # register the plugin
plugin_pool.register_plugin(OnePagePluginPublisher)  # register the plugin