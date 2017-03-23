from django.db import models

# Create your models here.
class Bannervideo(models.Model):
  name = models.CharField(max_length=255)
  bannerurl = models.CharField(max_length=255)
  is_active = models.BooleanField(default=False)
  link_fb = models.CharField(max_length=255, blank=True)
  link_google = models.CharField(max_length=255, blank=True)
  link_twitter = models.CharField(max_length=255, blank=True)

  def __unicode__(self):
    return self.name