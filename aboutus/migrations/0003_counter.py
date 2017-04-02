# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('aboutus', '0002_auto_20170327_1701'),
    ]

    operations = [
        migrations.CreateModel(
            name='Counter',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=1, choices=[(b'U', b'User'), (b'S', b'Project Success'), (b'C', b'Communication'), (b'T', b'Time Sharing')])),
                ('value', models.IntegerField()),
            ],
        ),
    ]
