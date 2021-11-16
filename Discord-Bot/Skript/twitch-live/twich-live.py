import json

import self as self
from discord.ext import commands
from discord.ext.tasks import loop
from twitch import get_notications

bot = commands.Bot(command_prefix="!")


@bot.command()
async def ping(ctx):
    await ctx.send("pong")

@loop(seconds=90)
async def check_twitch_online_streamers():
    channel = await bot.fetch_channel(910123877802311720)
    print(channel, 'get channel')
    if not channel:
        return
        print('channel is null')
    notifications = get_notications()
    print(notifications)
    for notification in notifications:
        await channel.send("Streamer {} ist jetzt Online!: {}".format(notifications["user_login"], notification))
        print('channel send')

with open("config.json") as config_file:
    config = json.load(config_file)

if __name__ == "__main__":
    check_twitch_online_streamers.start()
    bot.run(config["discord_token"])



