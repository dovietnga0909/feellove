# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('bannervideo', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='bannervideo',
            name='bannerurl',
            field=models.CharField(max_length=255),
        ),
        migrations.AlterField(
            model_name='bannervideo',
            name='link_fb',
            field=models.CharField(max_length=255, blank=True),
        ),
        migrations.AlterField(
            model_name='bannervideo',
            name='link_google',
            field=models.CharField(max_length=255, blank=True),
        ),
        migrations.AlterField(
            model_name='bannervideo',
            name='link_twitter',
            field=models.CharField(max_length=255, blank=True),
        ),
    ]
