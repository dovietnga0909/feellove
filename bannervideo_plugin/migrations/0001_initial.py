# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('cms', '0016_auto_20160608_1535'),
        ('bannervideo', '0001_initial'),
    ]

    operations = [
        migrations.CreateModel(
            name='BannervideoPluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='bannervideo_plugin_bannervideopluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('banner', models.ForeignKey(to='bannervideo.Bannervideo')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]
