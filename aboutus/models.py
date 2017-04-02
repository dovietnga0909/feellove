from django.db import models

class Aboutus(models.Model):
  name = models.CharField(max_length=255)
  caption = models.CharField(max_length=255)
  sub_caption = models.CharField(max_length=255,blank=True)
  description = models.TextField(blank=True)
  image1 = models.FileField( blank=True)
  image2 = models.FileField( blank=True)
  image3 = models.FileField( blank=True)

  def __unicode__(self):
    return self.name

class Counter(models.Model):
  KIND_OF_COUNTERS = (
    ('U', 'User'),
    ('S', 'Project Success'),
    ('C', 'Communication'),
    ('T', 'Time Sharing'),
  )
  name = models.CharField(max_length=1, choices=KIND_OF_COUNTERS)
  value = models.IntegerField()
