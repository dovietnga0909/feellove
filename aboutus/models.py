from django.db import models

class Aboutus(models.Model):
  name = models.CharField(max_length=255)
  caption = models.CharField(max_length=255)
  description = models.TextField(blank=True)
  image = models.TextField( blank=True)

  def __unicode__(self):
    return self.name