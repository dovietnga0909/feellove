# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('cms', '0028_auto_20170402_2141'),
        ('partner', '0001_initial'),
        ('one_page_plugin', '0002_contactpluginmodel'),
    ]

    operations = [
        migrations.CreateModel(
            name='PartnerPluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_partnerpluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('partner', models.ForeignKey(to='partner.Partner')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]
