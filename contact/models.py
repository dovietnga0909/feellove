from django.db import models

# Create your models here.
class Contact(models.Model):
  phone = models.IntegerField()
  address = models.TextField()
  email = models.TextField()
  link_google_map = models.TextField(blank=True)