# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('cms', '0029_auto_20170402_2153'),
        ('service', '0001_initial'),
        ('one_page_plugin', '0003_partnerpluginmodel'),
    ]

    operations = [
        migrations.CreateModel(
            name='ServicePluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_servicepluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('service', models.ForeignKey(to='service.Service')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]
