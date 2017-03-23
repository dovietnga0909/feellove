from django.db import models
from cms.models import CMSPlugin
from bannervideo.models import Bannervideo
# Create your models here.

class BannervideoPluginModel(CMSPlugin):
  banner = models.ForeignKey(Bannervideo)
  def __unicode__(self):
    return self.banner.name