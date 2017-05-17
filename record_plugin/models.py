from django.db import models
from cms.models import CMSPlugin
from record.models import Record
# Create your models here.

class RecordPluginModel(CMSPlugin):
  record = models.ForeignKey(Record)
  def __unicode__(self):
    return self.record.name