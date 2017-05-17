from django.db import models
from django.contrib.auth.models import User
# Create your models here.
class Record(models.Model):
  name = models.CharField(max_length=255)
  tags = models.TextField(blank=True)
  sound = models.TextField(blank=True)
  created_by = models.ForeignKey(User, unique=True,blank=True)
  created_at = models.TimeField(auto_now=True)

  def __unicode__(self):
    return self.name