from django.db import models
from cms.models import CMSPlugin
from aboutus.models import Aboutus
# Create your models here.

class OnePagePluginModel(CMSPlugin):
  aboutus = models.ForeignKey(Aboutus)
  def __unicode__(self):
    return self.aboutus.name