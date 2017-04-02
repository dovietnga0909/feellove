from django.db import models
from cms.models import CMSPlugin
from aboutus.models import Aboutus
from contact.models import Contact
from partner.models import Partner
from service.models import Service
# Create your models here.

class OnePagePluginModel(CMSPlugin):
  aboutus = models.ForeignKey(Aboutus)
  def __unicode__(self):
    return self.aboutus.name

class ContactPluginModel(CMSPlugin):
  contact = models.ForeignKey(Contact)
  def __unicode__(self):
    return self.contact.email

class PartnerPluginModel(CMSPlugin):
  partner = models.ForeignKey(Partner)
  def __unicode__(self):
    return self.partner.name

class ServicePluginModel(CMSPlugin):
  service = models.ForeignKey(Service)
  def __unicode__(self):
    return self.service.name