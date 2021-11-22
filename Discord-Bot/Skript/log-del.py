import discord


intents = discord.Intents.all()
intents.members = True
intents.messages = True
intents.presences = True
client = discord.Client(intents=discord.Intents.all())
g = client.get_guild(759212028744695808)

@client.event
async def on_ready():
    global g
    arr = [client.get_channel(901942693750509578)]
    for channel in arr:
        print('Clearing messages...')
        await channel.purge(limit=100000)
    else:
        print(channel, 'Keine Einträge gefunden')


async def save_audit_logs(guild):
    with open(f'audit_logs_{guild.name}', 'w+') as f:
        async for entry in guild.audit_logs(limit=100):
            f.write('{0.user} did {0.action} to {0.target}'.format(entry))


@client.event
async def on_message(message):
    if message.content.startswith('audit'):
        await save_audit_logs(message.channel.guild)

client.run('OTEwMTIxNjc1MjQ3NDYwMzky.YZOO3A.HqoFzMTM2c9EPO8FUniQDFu1NfY')