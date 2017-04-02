from django.db import models

# Create your models here.
class Partner(models.Model):
  name = models.CharField(max_length=255)
  image = models.FileField(blank=True)
  description = models.TextField(blank=True)

  def __unicode__(self):
    return self.name