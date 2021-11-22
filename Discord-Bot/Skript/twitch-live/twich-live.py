import json

import discord
from discord import client
from discord.ext.tasks import loop
import twitch
from twitch import get_notifications

#bot = commands.Bot(command_prefix="$")
intents = discord.Intents.all()
intents.members = True
intents.messages = True
intents.presences = True
client = discord.Client(intents=discord.Intents.all())
g = client.get_guild(759212028744695808)

#@bot.command()
#async def ping(ctx):
#    await ctx.send("pong")


@loop(seconds=90)
@client.event
async def check_twitch_online_streamers():
    global g
    channel = client.get_channel(910123877802311720)
    print(channel, 'Kanal')
    if not channel:
        return

    notifications = get_notifications()
    print(notifications)
    for notification in notifications:
        print(notification, 'Benachrichtigungen')
        await channel.send("streamer {} ist jetzt online: {}".format(notification["user_login"], notification))


with open("config.json") as config_file:
    config = json.load(config_file)

if __name__ == "__main__":
    check_twitch_online_streamers.start()
    client.run(config["discord_token"])