-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `effects_vanilla`;
CREATE TABLE `effects_vanilla` (
  `id` char(8) NOT NULL,
  `editorId` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `poison` tinyint(1) unsigned NOT NULL,
  `amplify` enum('magnitude','duration') NOT NULL,
  `baseCost` decimal(8,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `editorId` (`editorId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `effects_vanilla` (`id`, `editorId`, `name`, `description`, `poison`, `amplify`, `baseCost`) VALUES
('000AE722',	'AlchCureDisease',	'Cure Disease',	'Cures all diseases.',	0,	'magnitude',	2.2000),
('0003EB42',	'AlchDamageHealth',	'Damage Health',	'Damages the target\'s Health by <mag> points.',	1,	'magnitude',	3.0000),
('0003A2B6',	'AlchDamageMagicka',	'Damage Magicka',	'Damages the target\'s Magicka by <mag> points.',	1,	'magnitude',	2.2000),
('00073F2B',	'AlchDamageMagickaRate',	'Damage Magicka Regen',	'Decreases the target\'s Magicka regeneration by <mag>% for <dur> seconds.',	1,	'duration',	0.5000),
('0003A2C6',	'AlchDamageStamina',	'Damage Stamina',	'Damages the target\'s Stamina by <mag> points.',	1,	'magnitude',	1.8000),
('00073F2C',	'AlchDamageStaminaRate',	'Damage Stamina Regen',	'Decreases the target\'s Stamina regeneration by <mag>% for <dur> seconds.',	1,	'duration',	0.3000),
('00073F20',	'AlchInfluenceConfDown',	'Fear',	'Creatures and people up to level <mag> flee from combat for <dur> seconds.',	1,	'magnitude',	5.0000),
('0003EB24',	'AlchFortifyAlteration',	'Fortify Alteration',	'Alteration spells last <mag>% longer for <dur> seconds.',	0,	'magnitude',	0.2000),
('0003EB23',	'AlchFortifyBarter',	'Fortify Barter',	'You haggle for <mag>% better prices for <dur> seconds.',	0,	'magnitude',	2.0000),
('0003EB1C',	'AlchFortifyBlock',	'Fortify Block',	'Blocking absorbs <mag>% more damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB01',	'AlchFortifyCarryWeight',	'Fortify Carry Weight',	'Carrying capacity increases by <mag> for <dur> seconds.',	0,	'magnitude',	0.1500),
('0003EB25',	'AlchFortifyConjuration',	'Fortify Conjuration',	'Conjurations spells last <mag>% longer for <dur> seconds.',	0,	'magnitude',	0.2500),
('0003EB26',	'AlchFortifyDestruction',	'Fortify Destruction',	'Destruction spells are <mag>% stronger for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB29',	'AlchFortifyEnchanting',	'Fortify Enchanting',	'For <dur> seconds, items are enchanted <mag>% stronger.',	0,	'magnitude',	0.6000),
('0003EAF3',	'AlchFortifyHealth',	'Fortify Health',	'Health is increased by <mag> points for <dur> seconds.',	0,	'magnitude',	0.3500),
('0003EB1E',	'AlchFortifyHeavyArmor',	'Fortify Heavy Armor',	'Increases Heavy Armor skill by <mag> points for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB27',	'AlchFortifyIllusion',	'Fortify Illusion',	'Illusion spells are <mag>% stronger for <dur> seconds.',	0,	'magnitude',	0.4000),
('0003EB1F',	'AlchFortifyLightArmor',	'Fortify Light Armor',	'Increases Light Armor skill by <mag> points for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB21',	'AlchFortifyLockpicking',	'Fortify Lockpicking',	'Lockpicking is <mag>% easier for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EAF8',	'AlchFortifyMagicka',	'Fortify Magicka',	'Magicka is increased by <mag> points for <dur> seconds.',	0,	'magnitude',	0.3000),
('0003EB1B',	'AlchFortifyMarksman',	'Fortify Marksman',	'Ranged weapons deal <mag>% more damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB19',	'AlchFortifyOneHanded',	'Fortify One-Handed',	'One-handed weapons do <mag>% more damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB20',	'AlchFortifyPickpocket',	'Fortify Pickpocket',	'Pickpocketing is <mag>% easier for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB28',	'AlchFortifyRestoration',	'Fortify Restoration',	'Restoration spells are <mag>% stronger for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB1D',	'AlchFortifySmithing',	'Fortify Smithing',	'Weapon and armor improving is <mag>% better for <dur> seconds.',	0,	'magnitude',	0.7500),
('0003EB22',	'AlchFortifySneak',	'Fortify Sneak',	'You are <mag>% harder to detect for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EAF9',	'AlchFortifyStamina',	'Fortify Stamina',	'Stamina is increased by <mag> points for <dur> seconds.',	0,	'magnitude',	0.3000),
('0003EB1A',	'AlchFortifyTwoHanded',	'Fortify Two-Handed',	'Two-handed weapons do <mag>% more damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('00073F29',	'AlchInfluenceAggUp',	'Frenzy',	'Creatures and people up to level <mag> will attack anything nearby for <dur> seconds.',	1,	'magnitude',	15.0000),
('0003EB3D',	'AlchInvisibillity',	'Invisibility',	'Invisibility for <dur> seconds.',	0,	'duration',	100.0000),
('0010AA4A',	'AlchDamageHealthDuration',	'Lingering Damage Health',	'Causes <mag> points of poison damage for <dur> seconds.',	1,	'magnitude',	12.0000),
('0010DE5F',	'AlchDamageMagickaDuration',	'Lingering Damage Magicka',	'Drains the target\'s Magicka by <mag> points per second for <dur> seconds.',	1,	'magnitude',	10.0000),
('0010DE5E',	'AlchDamageStaminaDuration',	'Lingering Damage Stamina',	'Drain the target\'s Stamina by <mag> points per second for <dur> seconds.',	1,	'magnitude',	1.8000),
('00073F30',	'AlchParalysis',	'Paralysis',	'Target is paralyzed for <dur> seconds.',	1,	'duration',	500.0000),
('00073F26',	'AlchDamageHealthRavage',	'Ravage Health',	'Concentrated poison drains Health by <mag> points per second for <dur> seconds.',	1,	'magnitude',	0.4000),
('00073F27',	'AlchDamageMagickaRavage',	'Ravage Magicka',	'Concentrated poison drains Magicka by <mag> points per second for <dur> seconds.',	1,	'magnitude',	1.0000),
('00073F23',	'AlchDamageStaminaRavage',	'Ravage Stamina',	'Concentrated poison drains Stamina by <mag> points per second for <dur> seconds.',	1,	'magnitude',	1.6000),
('0003EB06',	'AlchFortifyHealRate',	'Regenerate Health',	'Health regenerates <mag>% faster for <dur> seconds.',	0,	'magnitude',	0.1000),
('0003EB07',	'AlchFortifyMagickaRate',	'Regenerate Magicka',	'Magicka regenerates <mag>% faster for <dur> seconds.',	0,	'magnitude',	0.1000),
('0003EB08',	'AlchFortifyStaminaRate',	'Regenerate Stamina',	'Stamina regenerates <mag>% faster for <dur> seconds.',	0,	'magnitude',	0.1000),
('0003EAEA',	'AlchResistFire',	'Resist Fire',	'Resist <mag>% of fire damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EAEB',	'AlchResistFrost',	'Resist Frost',	'Resist <mag>% of frost damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('00039E51',	'AlchResistMagic',	'Resist Magic',	'Resist <mag>% of magic for <dur> seconds.',	0,	'magnitude',	1.0000),
('00090041',	'AlchResistPoison',	'Resist Poison',	'Resist <mag>% of poison for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EAEC',	'AlchResistShock',	'Resist Shock',	'Resist <mag>% of shock damage for <dur> seconds.',	0,	'magnitude',	0.5000),
('0003EB15',	'AlchRestoreHealth',	'Restore Health',	'Restore <mag> points of Health.',	0,	'magnitude',	0.5000),
('0003EB17',	'AlchRestoreMagicka',	'Restore Magicka',	'Restore <mag> points of Magicka.',	0,	'magnitude',	0.6000),
('0003EB16',	'AlchRestoreStamina',	'Restore Stamina',	'Restore <mag> Stamina.',	0,	'magnitude',	0.6000),
('00073F25',	'AlchDamageSpeed',	'Slow',	'Target moves at 50% speed for <dur> seconds.',	1,	'duration',	1.0000),
('0003AC2D',	'AlchWaterbreathing',	'Waterbreathing',	'Can breathe underwater for <dur> seconds.',	0,	'duration',	30.0000),
('00073F2D',	'AlchWeaknessFire',	'Weakness to Fire',	'Target is <mag>% weaker to fire damage for <dur> seconds.',	1,	'magnitude',	0.6000),
('00073F2E',	'AlchWeaknessFrost',	'Weakness to Frost',	'Target is <mag>% weaker to frost damage for <dur> seconds.',	1,	'magnitude',	0.5000),
('00073F51',	'AlchWeaknessMagic',	'Weakness to Magic',	'Target is <mag>% weaker to magic for <dur> seconds.',	1,	'magnitude',	1.0000),
('00090042',	'AlchWeaknessPoison',	'Weakness to Poison',	'Target is <mag>% weaker to poison for <dur> seconds.',	1,	'magnitude',	1.0000),
('00073F2F',	'AlchWeaknessShock',	'Weakness to Shock',	'Target is <mag>% weaker to shock damage for <dur> seconds.',	1,	'magnitude',	0.7000),
('00109ADD',	'AlchCurePoison',	'Cure Poison',	'Stops poison\'s continuing effects.',	0,	'magnitude',	0.2000),
('000D6947',	'AlchFortifyPersuasion',	'Fortify Persuasion',	'Increases Speech skill by <mag> points for <dur> seconds.',	0,	'magnitude',	0.5000);

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `id` char(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dlc` char(2) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ingredients` (`id`, `name`, `dlc`) VALUES
('xx03CD8E',	'Felsaad Tern Feathers',	'DB'),
('0006BC00',	'Mudcrab Chitin',	''),
('0003AD76',	'Vampire Dust',	''),
('00052695',	'Charred Skeever Hide',	''),
('000E7ED0',	'Hawk Feathers',	''),
('0001BCBC',	'Jarrin Root',	''),
('00106E1A',	'River Betty',	''),
('00059B86',	'Nirnroot',	''),
('000B701A',	'Crimson Nirnroot',	''),
('000516C8',	'Deathbell',	''),
('0003AD63',	'Ectoplasm',	''),
('xx01FF75',	'Emperor Parasol Moss',	'DB'),
('0003AD5D',	'Falmer Ear',	''),
('001016B3',	'Human Flesh',	''),
('000B18CD',	'Human Heart',	''),
('0004DA23',	'Imp Stool',	''),
('0002F44C',	'Nightshade',	''),
('xx0185FB',	'Poison Bloom',	'DG'),
('00077E1D',	'Red Mountain Flower',	''),
('0003AD6F',	'Skeever Tail',	''),
('0006BC0B',	'Small Antlers',	''),
('0003AD72',	'Troll Fat',	''),
('0003AD60',	'Void Salts',	''),
('000727E0',	'Butterfly Wing',	''),
('0003AD56',	'Chaurus Eggs',	''),
('0003AD5B',	'Daedra Heart',	''),
('0006BC07',	'Eye of Sabre Cat',	''),
('0003AD73',	'Glow Dust',	''),
('0003AD66',	'Hagraven Feathers',	''),
('00057F91',	'Hanging Moss',	''),
('000727DF',	'Luna Moth Wing',	''),
('0004DA24',	'Namira\'s Rot',	''),
('0007EDF5',	'Nordic Barnacle',	''),
('xx017008',	'Trama Root',	'DB'),
('xx0059BA',	'Ancestor Moth Wing',	'DG'),
('0006BC02',	'Bear Claws',	''),
('000727DE',	'Blue Butterfly Wing',	''),
('00077E1C',	'Blue Mountain Flower',	''),
('xx01CD6E',	'Burnt Spriggan Wood',	'DB'),
('xx0183B7',	'Chaurus Hunter Antennae',	'DG'),
('00023D77',	'Chicken\'s Egg',	''),
('xx00F1CC',	'Hawk\'s Egg',	'HF'),
('0009151B',	'Spider Egg',	''),
('00063B5F',	'Spriggan Sap',	''),
('xx01CD74',	'Ash Creep Cluster',	'DB'),
('000705B7',	'Berit\'s Ashes',	''),
('0004DA25',	'Blisterwort',	''),
('00034CDD',	'Bone Meal',	''),
('0006ABCB',	'Canis Root',	''),
('00106E19',	'Cyrodilic Spadetail',	''),
('0003AD64',	'Giant\'s Toe',	''),
('0007E8C8',	'Rock Warbler Egg',	''),
('000B2183',	'Creep Cluster',	''),
('00034D32',	'Frost Mirriam',	''),
('00106E18',	'Histcarp',	''),
('0005076E',	'Juniper Berries',	''),
('0006BC0A',	'Large Antlers',	''),
('00106E1C',	'Silverside Perch',	''),
('0004B0BA',	'Wheat',	''),
('xx002A78',	'Yellow Mountain Flower',	'DG'),
('000E4F0C',	'Blue Dartwing',	''),
('xx00B097',	'Gleamblossom',	'DG'),
('xx01CD72',	'Netch Jelly',	'DB'),
('0006BC10',	'Powdered Mammoth Tusk',	''),
('00083E64',	'Grass Pod',	''),
('000889A2',	'Dragon\'s Tongue',	''),
('0006B689',	'Hagraven Claw',	''),
('0003F7F8',	'Tundra Cotton',	''),
('0004DA20',	'Bleeding Crown',	''),
('xx01CD6F',	'Boar Tusk',	'DB'),
('000854FE',	'Pearl',	''),
('0003AD70',	'Slaughterfish Scales',	''),
('0003AD61',	'Briar Heart',	''),
('000B08C5',	'Honeycomb',	''),
('000E7EBC',	'Hawk Beak',	''),
('0006F950',	'Scaly Pholiota',	''),
('0006BC0E',	'Wisp Wrappings',	''),
('0003AD5F',	'Frost Salts',	''),
('00045C28',	'Lavender',	''),
('000A9191',	'Beehive Husk',	''),
('0007EE01',	'Glowing Mushroom',	''),
('0001B3BD',	'Snowberries',	''),
('xx01CD6D',	'Spawn Ash',	'DB'),
('0003AD6A',	'Ice Wraith Teeth',	''),
('0006BC04',	'Sabre Cat Tooth',	''),
('000134AA',	'Thistle Branch',	''),
('0004DA22',	'White Cap',	''),
('000F11C0',	'Dwarven Oil',	''),
('000EC870',	'Mora Tapinella',	''),
('0003AD71',	'Taproot',	''),
('xx01CD71',	'Ash Hopper Jelly',	'DB'),
('xx016E26',	'Ashen Grass Pod',	'DB'),
('00023D6F',	'Pine Thrush Egg',	''),
('0006AC4A',	'Jazbay Grapes',	''),
('xx003545',	'Salmon Roe',	'HF'),
('00034D31',	'Elves Ear',	''),
('00085500',	'Small Pearl',	''),
('000BB956',	'Orange Dartwing',	''),
('0007E8C5',	'Slaughterfish Egg',	''),
('00106E1B',	'Abecean Longfin',	''),
('00034CDF',	'Salt Pile',	''),
('00077E1E',	'Purple Mountain Flower',	''),
('00034D22',	'Garlic',	''),
('0004DA73',	'Torchbug Thorax',	''),
('0004DA00',	'Fly Amanita',	''),
('xx017E97',	'Scathecraw',	'DB'),
('0007E8B7',	'Swamp Fungal Pod',	''),
('0007E8C1',	'Giant Lichen',	''),
('000A9195',	'Bee',	''),
('0003AD5E',	'Fire Salts',	''),
('000D8E3F',	'Moon Sugar',	'');

DROP TABLE IF EXISTS `ingredients_vanilla`;
CREATE TABLE `ingredients_vanilla` (
  `id` char(8) NOT NULL,
  `effectId` char(8) NOT NULL,
  `magnitude` decimal(12,8) NOT NULL,
  `duration` decimal(12,8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ingredients_vanilla` (`id`, `effectId`, `magnitude`, `duration`) VALUES
('xx03CD8E',	'000AE722',	5.00000000,	0.00000000),
('0006BC00',	'000AE722',	5.00000000,	0.00000000),
('0003AD76',	'000AE722',	5.00000000,	0.00000000),
('00052695',	'000AE722',	5.00000000,	0.00000000),
('000E7ED0',	'000AE722',	5.00000000,	0.00000000),
('0001BCBC',	'0003EB42',	200.00000000,	1.00000000),
('00106E1A',	'0003EB42',	5.00000000,	1.00000000),
('00059B86',	'0003EB42',	2.00000000,	1.00000000),
('000B701A',	'0003EB42',	6.00000000,	1.00000000),
('000516C8',	'0003EB42',	3.00000000,	1.00000000),
('0003AD63',	'0003EB42',	2.00000000,	1.00000000),
('xx01FF75',	'0003EB42',	2.00000000,	1.00000000),
('0003AD5D',	'0003EB42',	2.00000000,	1.00000000),
('001016B3',	'0003EB42',	2.00000000,	1.00000000),
('000B18CD',	'0003EB42',	2.00000000,	1.00000000),
('0004DA23',	'0003EB42',	2.00000000,	1.00000000),
('0002F44C',	'0003EB42',	2.00000000,	1.00000000),
('xx0185FB',	'0003EB42',	3.00000000,	1.00000000),
('00077E1D',	'0003EB42',	2.00000000,	1.00000000),
('0003AD6F',	'0003EB42',	2.00000000,	1.00000000),
('0006BC0B',	'0003EB42',	2.00000000,	1.00000000),
('0003AD72',	'0003EB42',	2.00000000,	1.00000000),
('0003AD60',	'0003EB42',	2.00000000,	1.00000000),
('000727E0',	'0003A2B6',	3.00000000,	0.00000000),
('0003AD56',	'0003A2B6',	3.00000000,	0.00000000),
('0003AD5B',	'0003A2B6',	3.00000000,	0.00000000),
('0006BC07',	'0003A2B6',	3.00000000,	0.00000000),
('0003AD73',	'0003A2B6',	3.00000000,	0.00000000),
('0003AD66',	'0003A2B6',	3.00000000,	0.00000000),
('00057F91',	'0003A2B6',	3.00000000,	0.00000000),
('000B18CD',	'0003A2B6',	3.00000000,	0.00000000),
('0001BCBC',	'0003A2B6',	3.00000000,	0.00000000),
('000727DF',	'0003A2B6',	3.00000000,	0.00000000),
('0004DA24',	'0003A2B6',	3.00000000,	0.00000000),
('0007EDF5',	'0003A2B6',	3.00000000,	0.00000000),
('xx017008',	'0003A2B6',	3.00000000,	0.00000000),
('xx0059BA',	'00073F2B',	100.00000000,	5.00000000),
('0006BC02',	'00073F2B',	100.00000000,	5.00000000),
('000727DE',	'00073F2B',	100.00000000,	5.00000000),
('00077E1C',	'00073F2B',	100.00000000,	5.00000000),
('xx01CD6E',	'00073F2B',	100.00000000,	5.00000000),
('xx0183B7',	'00073F2B',	100.00000000,	5.00000000),
('00023D77',	'00073F2B',	100.00000000,	5.00000000),
('0003AD73',	'00073F2B',	100.00000000,	5.00000000),
('00057F91',	'00073F2B',	100.00000000,	5.00000000),
('xx00F1CC',	'00073F2B',	100.00000000,	5.00000000),
('000B18CD',	'00073F2B',	100.00000000,	5.00000000),
('0001BCBC',	'00073F2B',	100.00000000,	5.00000000),
('0002F44C',	'00073F2B',	100.00000000,	5.00000000),
('0009151B',	'00073F2B',	100.00000000,	5.00000000),
('00063B5F',	'00073F2B',	100.00000000,	5.00000000),
('000B701A',	'0003A2C6',	9.00000000,	0.00000000),
('xx0059BA',	'0003A2C6',	3.00000000,	0.00000000),
('xx01CD74',	'0003A2C6',	3.00000000,	0.00000000),
('000705B7',	'0003A2C6',	3.00000000,	0.00000000),
('0004DA25',	'0003A2C6',	3.00000000,	0.00000000),
('000727DE',	'0003A2C6',	3.00000000,	0.00000000),
('00034CDD',	'0003A2C6',	3.00000000,	0.00000000),
('0006ABCB',	'0003A2C6',	3.00000000,	0.00000000),
('xx0183B7',	'0003A2C6',	3.00000000,	0.00000000),
('00106E19',	'0003A2C6',	3.00000000,	0.00000000),
('0003AD64',	'0003A2C6',	3.00000000,	0.00000000),
('0001BCBC',	'0003A2C6',	3.00000000,	0.00000000),
('00059B86',	'0003A2C6',	3.00000000,	0.00000000),
('0007E8C8',	'0003A2C6',	3.00000000,	0.00000000),
('0009151B',	'0003A2C6',	3.00000000,	0.00000000),
('000B2183',	'00073F2C',	100.00000000,	5.00000000),
('0003AD5B',	'00073F2C',	100.00000000,	5.00000000),
('00034D32',	'00073F2C',	100.00000000,	5.00000000),
('0003AD64',	'00073F2C',	100.00000000,	5.00000000),
('00106E18',	'00073F2C',	100.00000000,	5.00000000),
('0005076E',	'00073F2C',	100.00000000,	5.00000000),
('0006BC0A',	'00073F2C',	100.00000000,	5.00000000),
('00106E1C',	'00073F2C',	100.00000000,	5.00000000),
('0003AD6F',	'00073F2C',	100.00000000,	5.00000000),
('0004B0BA',	'00073F2C',	100.00000000,	5.00000000),
('xx002A78',	'00073F2C',	100.00000000,	5.00000000),
('000E4F0C',	'00073F20',	1.00000000,	30.00000000),
('00106E19',	'00073F20',	1.00000000,	30.00000000),
('0003AD5B',	'00073F20',	1.00000000,	30.00000000),
('xx00B097',	'00073F20',	1.00000000,	30.00000000),
('0004DA24',	'00073F20',	1.00000000,	30.00000000),
('xx01CD72',	'00073F20',	1.00000000,	30.00000000),
('xx0185FB',	'00073F20',	1.00000000,	30.00000000),
('0006BC10',	'00073F20',	1.00000000,	30.00000000),
('xx01CD6E',	'0003EB24',	4.00000000,	60.00000000),
('00083E64',	'0003EB24',	4.00000000,	60.00000000),
('00106E1A',	'0003EB24',	4.00000000,	60.00000000),
('00063B5F',	'0003EB24',	4.00000000,	60.00000000),
('000727E0',	'0003EB23',	1.00000000,	30.00000000),
('000889A2',	'0003EB23',	1.00000000,	30.00000000),
('0006B689',	'0003EB23',	1.00000000,	30.00000000),
('0003F7F8',	'0003EB23',	1.00000000,	30.00000000),
('0004DA20',	'0003EB1C',	4.00000000,	60.00000000),
('xx01CD6F',	'0003EB1C',	4.00000000,	60.00000000),
('000854FE',	'0003EB1C',	4.00000000,	60.00000000),
('0003AD70',	'0003EB1C',	4.00000000,	60.00000000),
('0003F7F8',	'0003EB1C',	4.00000000,	60.00000000),
('0003AD61',	'0003EB1C',	2.00000000,	60.00000000),
('000B08C5',	'0003EB1C',	2.00000000,	60.00000000),
('000B2183',	'0003EB01',	4.00000000,	300.00000000),
('0003AD64',	'0003EB01',	4.00000000,	300.00000000),
('000E7EBC',	'0003EB01',	4.00000000,	300.00000000),
('xx01CD72',	'0003EB01',	4.00000000,	300.00000000),
('xx0185FB',	'0003EB01',	4.00000000,	300.00000000),
('00106E1A',	'0003EB01',	4.00000000,	300.00000000),
('0006F950',	'0003EB01',	4.00000000,	300.00000000),
('xx017008',	'0003EB01',	4.00000000,	300.00000000),
('0006BC0E',	'0003EB01',	4.00000000,	300.00000000),
('xx0059BA',	'0003EB25',	5.00000000,	60.00000000),
('000705B7',	'0003EB25',	5.00000000,	60.00000000),
('000727DE',	'0003EB25',	5.00000000,	60.00000000),
('00077E1C',	'0003EB25',	5.00000000,	60.00000000),
('00034CDD',	'0003EB25',	5.00000000,	60.00000000),
('xx0183B7',	'0003EB25',	5.00000000,	60.00000000),
('0003AD5F',	'0003EB25',	5.00000000,	60.00000000),
('0003AD66',	'0003EB25',	5.00000000,	60.00000000),
('00045C28',	'0003EB25',	5.00000000,	60.00000000),
('xx01CD74',	'0003EB26',	5.00000000,	60.00000000),
('000A9191',	'0003EB26',	5.00000000,	60.00000000),
('0003AD73',	'0003EB26',	5.00000000,	60.00000000),
('0007EE01',	'0003EB26',	5.00000000,	60.00000000),
('0006BC0E',	'0003EB26',	5.00000000,	60.00000000),
('0003AD63',	'0003EB26',	4.00000000,	60.00000000),
('0002F44C',	'0003EB26',	4.00000000,	60.00000000),
('xx0059BA',	'0003EB29',	1.00000000,	30.00000000),
('000727DE',	'0003EB29',	1.00000000,	30.00000000),
('xx0183B7',	'0003EB29',	1.00000000,	30.00000000),
('0006B689',	'0003EB29',	1.00000000,	30.00000000),
('0001B3BD',	'0003EB29',	1.00000000,	30.00000000),
('xx01CD6D',	'0003EB29',	1.00000000,	30.00000000),
('00063B5F',	'0003EB29',	1.00000000,	30.00000000),
('xx01CD6F',	'0003EAF3',	4.00000000,	60.00000000),
('0003AD64',	'0003EAF3',	4.00000000,	60.00000000),
('0006BC02',	'0003EAF3',	4.00000000,	60.00000000),
('00077E1C',	'0003EAF3',	4.00000000,	60.00000000),
('0007EE01',	'0003EAF3',	4.00000000,	60.00000000),
('00057F91',	'0003EAF3',	4.00000000,	60.00000000),
('0004B0BA',	'0003EAF3',	4.00000000,	60.00000000),
('xx002A78',	'0003EAF3',	4.00000000,	60.00000000),
('0003AD6A',	'0003EB1E',	2.00000000,	60.00000000),
('0006BC04',	'0003EB1E',	2.00000000,	60.00000000),
('0003AD70',	'0003EB1E',	2.00000000,	60.00000000),
('000134AA',	'0003EB1E',	2.00000000,	60.00000000),
('0004DA22',	'0003EB1E',	2.00000000,	60.00000000),
('000F11C0',	'0003EB27',	4.00000000,	60.00000000),
('000EC870',	'0003EB27',	4.00000000,	60.00000000),
('0006F950',	'0003EB27',	4.00000000,	60.00000000),
('0003AD71',	'0003EB27',	4.00000000,	60.00000000),
('000889A2',	'0003EB27',	4.00000000,	60.00000000),
('xx01CD71',	'0003EB1F',	2.00000000,	60.00000000),
('000A9191',	'0003EB1F',	2.00000000,	60.00000000),
('xx03CD8E',	'0003EB1F',	2.00000000,	60.00000000),
('000E7ED0',	'0003EB1F',	2.00000000,	60.00000000),
('000B08C5',	'0003EB1F',	2.00000000,	60.00000000),
('000727DF',	'0003EB1F',	2.00000000,	60.00000000),
('0003AD6F',	'0003EB1F',	2.00000000,	60.00000000),
('xx016E26',	'0003EB21',	2.00000000,	30.00000000),
('0003AD5D',	'0003EB21',	2.00000000,	30.00000000),
('0004DA24',	'0003EB21',	2.00000000,	30.00000000),
('00023D6F',	'0003EB21',	2.00000000,	30.00000000),
('0009151B',	'0003EB21',	2.00000000,	30.00000000),
('0003AD61',	'0003EAF8',	4.00000000,	60.00000000),
('0003AD63',	'0003EAF8',	4.00000000,	60.00000000),
('xx01FF75',	'0003EAF8',	4.00000000,	60.00000000),
('00106E18',	'0003EAF8',	4.00000000,	60.00000000),
('0006AC4A',	'0003EAF8',	4.00000000,	60.00000000),
('00077E1D',	'0003EAF8',	4.00000000,	60.00000000),
('xx003545',	'0003EAF8',	4.00000000,	60.00000000),
('0003F7F8',	'0003EAF8',	4.00000000,	60.00000000),
('0003AD60',	'0003EAF8',	4.00000000,	60.00000000),
('0006ABCB',	'0003EB1B',	4.00000000,	60.00000000),
('00034D31',	'0003EB1B',	4.00000000,	60.00000000),
('0005076E',	'0003EB1B',	4.00000000,	60.00000000),
('0009151B',	'0003EB1B',	4.00000000,	60.00000000),
('0006BC02',	'0003EB19',	4.00000000,	60.00000000),
('0006ABCB',	'0003EB19',	4.00000000,	60.00000000),
('00057F91',	'0003EB19',	4.00000000,	60.00000000),
('000E7ED0',	'0003EB19',	4.00000000,	60.00000000),
('0007E8C8',	'0003EB19',	4.00000000,	60.00000000),
('00085500',	'0003EB19',	4.00000000,	60.00000000),
('000E4F0C',	'0003EB20',	4.00000000,	60.00000000),
('0007EDF5',	'0003EB20',	4.00000000,	60.00000000),
('000BB956',	'0003EB20',	4.00000000,	60.00000000),
('0007E8C5',	'0003EB20',	4.00000000,	60.00000000),
('00106E1B',	'0003EB28',	4.00000000,	60.00000000),
('00106E19',	'0003EB28',	4.00000000,	60.00000000),
('00034CDF',	'0003EB28',	4.00000000,	60.00000000),
('0006BC0B',	'0003EB28',	4.00000000,	60.00000000),
('00085500',	'0003EB28',	4.00000000,	60.00000000),
('xx002A78',	'0003EB28',	4.00000000,	60.00000000),
('0004DA25',	'0003EB1D',	4.00000000,	30.00000000),
('0007EE01',	'0003EB1D',	4.00000000,	30.00000000),
('0006BC04',	'0003EB1D',	4.00000000,	30.00000000),
('00063B5F',	'0003EB1D',	4.00000000,	30.00000000),
('00106E1B',	'0003EB22',	4.00000000,	60.00000000),
('xx016E26',	'0003EB22',	4.00000000,	60.00000000),
('00034D32',	'0003EB22',	4.00000000,	60.00000000),
('000E7ED0',	'0003EB22',	4.00000000,	60.00000000),
('001016B3',	'0003EB22',	4.00000000,	60.00000000),
('0006BC10',	'0003EB22',	4.00000000,	60.00000000),
('00077E1E',	'0003EB22',	4.00000000,	60.00000000),
('000A9191',	'0003EB22',	4.00000000,	60.00000000),
('xx01CD6F',	'0003EAF9',	5.00000000,	60.00000000),
('0003AD56',	'0003EAF9',	4.00000000,	60.00000000),
('00034D22',	'0003EAF9',	4.00000000,	60.00000000),
('0006BC0A',	'0003EAF9',	4.00000000,	60.00000000),
('00045C28',	'0003EAF9',	4.00000000,	60.00000000),
('0007E8C5',	'0003EAF9',	4.00000000,	60.00000000),
('0004DA73',	'0003EAF9',	4.00000000,	60.00000000),
('000889A2',	'0003EB1A',	4.00000000,	60.00000000),
('xx01FF75',	'0003EB1A',	4.00000000,	60.00000000),
('0004DA00',	'0003EB1A',	4.00000000,	60.00000000),
('0003AD72',	'0003EB1A',	4.00000000,	60.00000000),
('0004DA25',	'00073F29',	1.00000000,	10.00000000),
('xx01CD6F',	'00073F29',	1.00000000,	10.00000000),
('0003AD5D',	'00073F29',	1.00000000,	10.00000000),
('0004DA00',	'00073F29',	1.00000000,	10.00000000),
('0003AD66',	'00073F29',	1.00000000,	10.00000000),
('000B18CD',	'00073F29',	1.00000000,	10.00000000),
('0003AD72',	'00073F29',	1.00000000,	10.00000000),
('xx01CD74',	'0003EB3D',	0.00000000,	4.00000000),
('0003AD56',	'0003EB3D',	0.00000000,	4.00000000),
('000B701A',	'0003EB3D',	0.00000000,	4.00000000),
('0003AD6A',	'0003EB3D',	0.00000000,	4.00000000),
('000727DF',	'0003EB3D',	0.00000000,	4.00000000),
('00059B86',	'0003EB3D',	0.00000000,	4.00000000),
('0003AD76',	'0003EB3D',	0.00000000,	4.00000000),
('0004DA23',	'0010AA4A',	1.00000000,	10.00000000),
('000EC870',	'0010AA4A',	1.00000000,	10.00000000),
('000BB956',	'0010AA4A',	1.00000000,	10.00000000),
('xx017E97',	'0010AA4A',	1.00000000,	10.00000000),
('0007E8C5',	'0010AA4A',	1.00000000,	10.00000000),
('0003AD70',	'0010AA4A',	1.00000000,	10.00000000),
('0006B689',	'0010DE5F',	1.00000000,	10.00000000),
('00077E1E',	'0010DE5F',	1.00000000,	10.00000000),
('0007E8B7',	'0010DE5F',	1.00000000,	10.00000000),
('0004DA73',	'0010DE5F',	1.00000000,	10.00000000),
('0004B0BA',	'0010DE5F',	1.00000000,	10.00000000),
('000727E0',	'0010DE5E',	1.00000000,	10.00000000),
('00023D77',	'0010DE5E',	1.00000000,	10.00000000),
('xx00F1CC',	'0010DE5E',	1.00000000,	10.00000000),
('0002F44C',	'0010DE5E',	1.00000000,	10.00000000),
('0006BC0B',	'0010DE5E',	1.00000000,	10.00000000),
('0003AD61',	'00073F30',	0.00000000,	1.00000000),
('0006ABCB',	'00073F30',	0.00000000,	1.00000000),
('xx00B097',	'00073F30',	0.00000000,	1.00000000),
('001016B3',	'00073F30',	0.00000000,	1.00000000),
('0004DA23',	'00073F30',	0.00000000,	1.00000000),
('xx01CD72',	'00073F30',	0.00000000,	1.00000000),
('0007E8B7',	'00073F30',	0.00000000,	1.00000000),
('00106E19',	'00073F26',	2.00000000,	10.00000000),
('0006BC07',	'00073F26',	2.00000000,	10.00000000),
('0007E8C1',	'00073F26',	2.00000000,	10.00000000),
('0006AC4A',	'00073F26',	2.00000000,	10.00000000),
('xx017E97',	'00073F26',	2.00000000,	10.00000000),
('00106E1C',	'00073F26',	2.00000000,	10.00000000),
('0003AD6F',	'00073F26',	2.00000000,	10.00000000),
('00034D32',	'00073F27',	2.00000000,	10.00000000),
('00083E64',	'00073F27',	2.00000000,	10.00000000),
('00045C28',	'00073F27',	2.00000000,	10.00000000),
('000BB956',	'00073F27',	2.00000000,	10.00000000),
('00077E1D',	'00073F27',	2.00000000,	10.00000000),
('xx017E97',	'00073F27',	2.00000000,	10.00000000),
('xx01CD6D',	'00073F27',	2.00000000,	10.00000000),
('0004DA22',	'00073F27',	2.00000000,	10.00000000),
('000516C8',	'00073F23',	2.00000000,	10.00000000),
('000A9195',	'00073F23',	2.00000000,	10.00000000),
('000705B7',	'00073F23',	2.00000000,	10.00000000),
('00034CDD',	'00073F23',	2.00000000,	10.00000000),
('000B08C5',	'00073F23',	2.00000000,	10.00000000),
('xx017E97',	'00073F23',	2.00000000,	10.00000000),
('xx01CD6D',	'00073F23',	2.00000000,	10.00000000),
('000134AA',	'00073F23',	2.00000000,	10.00000000),
('xx01FF75',	'0003EB06',	5.00000000,	300.00000000),
('00034D22',	'0003EB06',	5.00000000,	300.00000000),
('xx00B097',	'0003EB06',	5.00000000,	300.00000000),
('0005076E',	'0003EB06',	5.00000000,	300.00000000),
('000727DF',	'0003EB06',	5.00000000,	300.00000000),
('0004DA24',	'0003EB06',	5.00000000,	300.00000000),
('0007EDF5',	'0003EB06',	5.00000000,	300.00000000),
('0003AD76',	'0003EB06',	5.00000000,	300.00000000),
('000F11C0',	'0003EB07',	5.00000000,	300.00000000),
('0003AD5E',	'0003EB07',	5.00000000,	300.00000000),
('00034D22',	'0003EB07',	5.00000000,	300.00000000),
('0006AC4A',	'0003EB07',	5.00000000,	300.00000000),
('000D8E3F',	'0003EB07',	5.00000000,	300.00000000),
('xx003545',	'0003EB07',	5.00000000,	300.00000000),
('00034CDF',	'0003EB07',	5.00000000,	300.00000000),
('0003AD71',	'0003EB07',	5.00000000,	300.00000000),
('000A9195',	'0003EB08',	5.00000000,	300.00000000),
('0004DA00',	'0003EB08',	5.00000000,	300.00000000),
('000EC870',	'0003EB08',	5.00000000,	300.00000000),
('0006F950',	'0003EB08',	5.00000000,	300.00000000),
('xx01CD74',	'0003EAEA',	3.00000000,	60.00000000),
('xx016E26',	'0003EAEA',	3.00000000,	60.00000000),
('000705B7',	'0003EAEA',	3.00000000,	60.00000000),
('00034CDD',	'0003EAEA',	3.00000000,	60.00000000),
('000889A2',	'0003EAEA',	3.00000000,	60.00000000),
('0003AD5E',	'0003EAEA',	3.00000000,	60.00000000),
('0004DA00',	'0003EAEA',	3.00000000,	60.00000000),
('0006BC00',	'0003EAEA',	3.00000000,	60.00000000),
('0001B3BD',	'0003EAEA',	3.00000000,	60.00000000),
('xx01CD6D',	'0003EAEA',	3.00000000,	60.00000000),
('00034D31',	'0003EAEA',	3.00000000,	60.00000000),
('00034D32',	'0003EAEB',	3.00000000,	60.00000000),
('0003AD5F',	'0003EAEB',	3.00000000,	60.00000000),
('000E7EBC',	'0003EAEB',	3.00000000,	60.00000000),
('000D8E3F',	'0003EAEB',	3.00000000,	60.00000000),
('00077E1E',	'0003EAEB',	3.00000000,	60.00000000),
('00106E1C',	'0003EAEB',	3.00000000,	60.00000000),
('0003AD70',	'0003EAEB',	3.00000000,	60.00000000),
('00085500',	'0003EAEB',	3.00000000,	60.00000000),
('0001B3BD',	'0003EAEB',	3.00000000,	60.00000000),
('000134AA',	'0003EAEB',	3.00000000,	60.00000000),
('0004DA20',	'00039E51',	1.00000000,	60.00000000),
('00023D77',	'00039E51',	1.00000000,	60.00000000),
('000B701A',	'00039E51',	1.00000000,	60.00000000),
('xx03CD8E',	'00039E51',	1.00000000,	60.00000000),
('xx00B097',	'00039E51',	1.00000000,	60.00000000),
('0006B689',	'00039E51',	1.00000000,	60.00000000),
('xx00F1CC',	'00039E51',	1.00000000,	60.00000000),
('00045C28',	'00039E51',	1.00000000,	60.00000000),
('00059B86',	'00039E51',	1.00000000,	60.00000000),
('0003F7F8',	'00039E51',	1.00000000,	60.00000000),
('0003AD60',	'00039E51',	1.00000000,	60.00000000),
('0006BC0E',	'00039E51',	1.00000000,	60.00000000),
('00052695',	'00090041',	4.00000000,	60.00000000),
('0003AD5D',	'00090041',	4.00000000,	60.00000000),
('00034D22',	'00090041',	4.00000000,	60.00000000),
('00083E64',	'00090041',	4.00000000,	60.00000000),
('0006BC00',	'00090041',	4.00000000,	60.00000000),
('0007E8C5',	'00090041',	4.00000000,	60.00000000),
('0003AD72',	'00090041',	4.00000000,	60.00000000),
('xx002A78',	'00090041',	4.00000000,	60.00000000),
('000A9191',	'00090041',	2.00000000,	60.00000000),
('000134AA',	'00090041',	3.00000000,	60.00000000),
('xx01CD71',	'0003EAEC',	3.00000000,	60.00000000),
('000E4F0C',	'0003EAEC',	3.00000000,	60.00000000),
('0003AD73',	'0003EAEC',	3.00000000,	60.00000000),
('0007EE01',	'0003EAEC',	3.00000000,	60.00000000),
('000E7EBC',	'0003EAEC',	3.00000000,	60.00000000),
('000854FE',	'0003EAEC',	3.00000000,	60.00000000),
('00023D6F',	'0003EAEC',	3.00000000,	60.00000000),
('0001B3BD',	'0003EAEC',	3.00000000,	60.00000000),
('0007E8B7',	'0003EAEC',	3.00000000,	60.00000000),
('xx01CD71',	'0003EB15',	5.00000000,	0.00000000),
('000E4F0C',	'0003EB15',	5.00000000,	0.00000000),
('00077E1C',	'0003EB15',	5.00000000,	0.00000000),
('000727E0',	'0003EB15',	5.00000000,	0.00000000),
('00052695',	'0003EB15',	5.00000000,	0.00000000),
('0003AD5B',	'0003EB15',	5.00000000,	0.00000000),
('0006BC07',	'0003EB15',	5.00000000,	0.00000000),
('xx03CD8E',	'0003EB15',	5.00000000,	0.00000000),
('0007E8C8',	'0003EB15',	5.00000000,	0.00000000),
('0007E8B7',	'0003EB15',	5.00000000,	0.00000000),
('0004B0BA',	'0003EB15',	5.00000000,	0.00000000),
('0004DA25',	'0003EB15',	3.00000000,	0.00000000),
('0004DA23',	'0003EB15',	3.00000000,	0.00000000),
('0003AD61',	'0003EB17',	5.00000000,	0.00000000),
('000B2183',	'0003EB17',	5.00000000,	0.00000000),
('000F11C0',	'0003EB17',	5.00000000,	0.00000000),
('0003AD63',	'0003EB17',	5.00000000,	0.00000000),
('00034D31',	'0003EB17',	5.00000000,	0.00000000),
('0003AD5E',	'0003EB17',	5.00000000,	0.00000000),
('0003AD5F',	'0003EB17',	5.00000000,	0.00000000),
('0007E8C1',	'0003EB17',	5.00000000,	0.00000000),
('00083E64',	'0003EB17',	5.00000000,	0.00000000),
('001016B3',	'0003EB17',	5.00000000,	0.00000000),
('000D8E3F',	'0003EB17',	5.00000000,	0.00000000),
('000EC870',	'0003EB17',	5.00000000,	0.00000000),
('000854FE',	'0003EB17',	5.00000000,	0.00000000),
('00077E1D',	'0003EB17',	5.00000000,	0.00000000),
('0003AD71',	'0003EB17',	5.00000000,	0.00000000),
('0003AD76',	'0003EB17',	5.00000000,	0.00000000),
('0004DA22',	'0003EB17',	5.00000000,	0.00000000),
('xx01CD72',	'0003EB16',	10.00000000,	0.00000000),
('000A9195',	'0003EB16',	5.00000000,	0.00000000),
('00052695',	'0003EB16',	5.00000000,	0.00000000),
('0006BC07',	'0003EB16',	5.00000000,	0.00000000),
('000E7EBC',	'0003EB16',	5.00000000,	0.00000000),
('00106E18',	'0003EB16',	5.00000000,	0.00000000),
('000B08C5',	'0003EB16',	5.00000000,	0.00000000),
('0006BC0A',	'0003EB16',	5.00000000,	0.00000000),
('0006BC00',	'0003EB16',	5.00000000,	0.00000000),
('000BB956',	'0003EB16',	5.00000000,	0.00000000),
('000854FE',	'0003EB16',	5.00000000,	0.00000000),
('00023D6F',	'0003EB16',	5.00000000,	0.00000000),
('0006BC10',	'0003EB16',	5.00000000,	0.00000000),
('00077E1E',	'0003EB16',	5.00000000,	0.00000000),
('0006BC04',	'0003EB16',	5.00000000,	0.00000000),
('00106E1C',	'0003EB16',	5.00000000,	0.00000000),
('00085500',	'0003EB16',	5.00000000,	0.00000000),
('0004DA73',	'0003EB16',	5.00000000,	0.00000000),
('0006BC0E',	'0003EB16',	5.00000000,	0.00000000),
('0006BC02',	'0003EB16',	4.00000000,	0.00000000),
('xx003545',	'0003EB16',	2.00000000,	0.00000000),
('xx01CD6E',	'00073F25',	50.00000000,	5.00000000),
('000516C8',	'00073F25',	50.00000000,	5.00000000),
('xx0185FB',	'00073F25',	50.00000000,	5.00000000),
('00106E1A',	'00073F25',	50.00000000,	5.00000000),
('00034CDF',	'00073F25',	50.00000000,	5.00000000),
('0006BC0A',	'00073F25',	50.00000000,	5.00000000),
('xx017008',	'00073F25',	50.00000000,	5.00000000),
('xx003545',	'0003AC2D',	0.00000000,	5.00000000),
('00023D77',	'0003AC2D',	0.00000000,	5.00000000),
('xx00F1CC',	'0003AC2D',	0.00000000,	5.00000000),
('00106E18',	'0003AC2D',	0.00000000,	5.00000000),
('0007EDF5',	'0003AC2D',	0.00000000,	5.00000000),
('0004DA20',	'00073F2D',	3.00000000,	30.00000000),
('xx01CD6E',	'00073F2D',	3.00000000,	30.00000000),
('0003AD5F',	'00073F2D',	3.00000000,	30.00000000),
('0003AD6A',	'00073F2D',	3.00000000,	30.00000000),
('0005076E',	'00073F2D',	3.00000000,	30.00000000),
('000D8E3F',	'00073F2D',	3.00000000,	30.00000000),
('0006BC10',	'00073F2D',	3.00000000,	30.00000000),
('00106E1B',	'00073F2E',	3.00000000,	30.00000000),
('xx01CD71',	'00073F2E',	3.00000000,	30.00000000),
('0003AD5E',	'00073F2E',	3.00000000,	30.00000000),
('0003AD6A',	'00073F2E',	3.00000000,	30.00000000),
('0004DA22',	'00073F2E',	3.00000000,	30.00000000),
('00034D31',	'00073F2E',	3.00000000,	30.00000000),
('000B2183',	'00073F51',	2.00000000,	30.00000000),
('000F11C0',	'00073F51',	2.00000000,	30.00000000),
('0006AC4A',	'00073F51',	2.00000000,	30.00000000),
('0007E8C8',	'00073F51',	2.00000000,	30.00000000),
('00034CDF',	'00073F51',	2.00000000,	30.00000000),
('0006F950',	'00073F51',	2.00000000,	30.00000000),
('0003AD71',	'00073F51',	2.00000000,	30.00000000),
('0004DA73',	'00073F51',	2.00000000,	30.00000000),
('00106E1B',	'00090042',	2.00000000,	30.00000000),
('0004DA20',	'00090042',	2.00000000,	30.00000000),
('0003AD56',	'00090042',	2.00000000,	30.00000000),
('000516C8',	'00090042',	2.00000000,	30.00000000),
('0007E8C1',	'00090042',	2.00000000,	30.00000000),
('00023D6F',	'00090042',	2.00000000,	30.00000000),
('0006BC04',	'00090042',	2.00000000,	30.00000000),
('0006BC0B',	'00090042',	2.00000000,	30.00000000),
('xx016E26',	'00073F2F',	3.00000000,	30.00000000),
('000A9195',	'00073F2F',	3.00000000,	30.00000000),
('0007E8C1',	'00073F2F',	3.00000000,	30.00000000),
('0003AD66',	'00073F2F',	3.00000000,	30.00000000),
('xx017008',	'00073F2F',	3.00000000,	30.00000000),
('0003AD60',	'00073F2F',	3.00000000,	30.00000000);

-- 2015-04-08 02:45:31