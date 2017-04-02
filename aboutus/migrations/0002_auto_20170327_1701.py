# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('aboutus', '0001_initial'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='aboutus',
            name='image',
        ),
        migrations.AddField(
            model_name='aboutus',
            name='image1',
            field=models.FileField(upload_to=b'', blank=True),
        ),
        migrations.AddField(
            model_name='aboutus',
            name='image2',
            field=models.FileField(upload_to=b'', blank=True),
        ),
        migrations.AddField(
            model_name='aboutus',
            name='image3',
            field=models.FileField(upload_to=b'', blank=True),
        ),
        migrations.AddField(
            model_name='aboutus',
            name='sub_caption',
            field=models.CharField(max_length=255, blank=True),
        ),
    ]
