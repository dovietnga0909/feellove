# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('cms', '0019_auto_20170324_1817'),
        ('aboutus', '0001_initial'),
    ]

    operations = [
        migrations.CreateModel(
            name='OnePagePluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_onepagepluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('aboutus', models.ForeignKey(to='aboutus.Aboutus')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]
