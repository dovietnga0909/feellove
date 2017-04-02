from django.db import models
from cms.models import CMSPlugin
from chat.models import Room
# Create your models here.

class RoomPluginModel(CMSPlugin):
  room = models.ForeignKey(Room)

  def __unicode__(self):
    return self.room.name

