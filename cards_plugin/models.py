from django.db import models
from cms.models import CMSPlugin
from cards.models import Cards
# Create your models here.

class CardsPluginModel(CMSPlugin):
  cards = models.ForeignKey(Cards)
  def __unicode__(self):
    return self.cards.name