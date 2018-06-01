
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fun_o_rama`
--
CREATE DATABASE IF NOT EXISTS `fun_o_rama` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `fun_o_rama`;

-- --------------------------------------------------------

--
-- Table structure for table `adjectives`
--

DROP TABLE IF EXISTS `adjectives`;
CREATE TABLE `adjectives` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `adjectives`
--

INSERT INTO `adjectives` (`id`, `name`) VALUES
(1, 'aback'),
(2, 'abaft'),
(3, 'abandoned'),
(4, 'abashed'),
(5, 'aberrant'),
(6, 'abhorrent'),
(7, 'abiding'),
(8, 'abject'),
(9, 'ablaze'),
(10, 'able'),
(11, 'abnormal'),
(12, 'aboard'),
(13, 'aboriginal'),
(14, 'abortive'),
(15, 'abounding'),
(16, 'abrasive'),
(17, 'abrupt'),
(18, 'absent'),
(19, 'absorbed'),
(20, 'absorbing'),
(21, 'abstracted'),
(22, 'absurd'),
(23, 'abundant'),
(24, 'abusive'),
(25, 'acceptable'),
(26, 'accessible'),
(27, 'accidental'),
(28, 'accurate'),
(29, 'acid'),
(30, 'acidic'),
(31, 'acoustic'),
(32, 'acrid'),
(33, 'actually'),
(35, 'adamant'),
(36, 'adaptable'),
(37, 'addicted'),
(38, 'adhesive'),
(39, 'adjoining'),
(40, 'adorable'),
(41, 'adventurous'),
(42, 'afraid'),
(43, 'aggressive'),
(44, 'agonizing'),
(45, 'agreeable'),
(46, 'ahead'),
(47, 'ajar'),
(48, 'alcoholic'),
(49, 'alert'),
(50, 'alike'),
(51, 'alive'),
(52, 'alleged'),
(53, 'alluring'),
(54, 'aloof'),
(55, 'amazing'),
(56, 'ambiguous'),
(57, 'ambitious'),
(58, 'amuck'),
(59, 'amused'),
(60, 'amusing'),
(61, 'ancient'),
(62, 'angry'),
(63, 'animated'),
(64, 'annoyed'),
(65, 'annoying'),
(66, 'anxious'),
(67, 'apathetic'),
(68, 'aquatic'),
(69, 'aromatic'),
(70, 'arrogant'),
(71, 'ashamed'),
(72, 'aspiring'),
(73, 'assorted'),
(74, 'astonishing'),
(75, 'attractive'),
(76, 'auspicious'),
(77, 'automatic'),
(78, 'available'),
(79, 'average'),
(80, 'awake'),
(81, 'aware'),
(82, 'awesome'),
(83, 'awful'),
(84, 'axiomatic'),
(85, 'bad'),
(86, 'barbarous'),
(87, 'bashful'),
(88, 'bawdy'),
(89, 'beautiful'),
(90, 'befitting'),
(91, 'belligerent'),
(92, 'beneficial'),
(93, 'bent'),
(94, 'berserk'),
(95, 'best'),
(96, 'better'),
(97, 'bewildered'),
(98, 'big'),
(99, 'billowy'),
(101, 'bitter'),
(102, 'bizarre'),
(103, 'bloody'),
(105, 'blushing'),
(106, 'boiling'),
(107, 'boorish'),
(108, 'bored'),
(109, 'boring'),
(110, 'bouncy'),
(111, 'boundless'),
(112, 'brainy'),
(113, 'brash'),
(114, 'brave'),
(115, 'brawny'),
(116, 'breakable'),
(117, 'breezy'),
(118, 'brief'),
(119, 'bright'),
(120, 'bright'),
(121, 'broad'),
(122, 'broken'),
(123, 'brown'),
(124, 'bumpy'),
(125, 'burly'),
(126, 'bustling'),
(127, 'busy'),
(128, 'cagey'),
(129, 'calculating'),
(130, 'callous'),
(131, 'calm'),
(132, 'capable'),
(133, 'capricious'),
(134, 'careful'),
(135, 'careless'),
(136, 'caring'),
(137, 'cautious'),
(138, 'ceaseless'),
(139, 'certain'),
(140, 'changeable'),
(141, 'charming'),
(142, 'cheap'),
(143, 'cheerful'),
(144, 'chemical'),
(145, 'chief'),
(146, 'childlike'),
(147, 'chilly'),
(148, 'chivalrous'),
(149, 'chubby'),
(150, 'chunky'),
(151, 'clammy'),
(152, 'classy'),
(153, 'clean'),
(154, 'clear'),
(155, 'clever'),
(156, 'cloistered'),
(157, 'closed'),
(158, 'cloudy'),
(159, 'clumsy'),
(160, 'cluttered'),
(161, 'coherent'),
(162, 'cold'),
(163, 'colorful'),
(164, 'colossal'),
(165, 'combative'),
(166, 'comfortable'),
(167, 'common'),
(168, 'complete'),
(169, 'complex'),
(170, 'concerned'),
(171, 'condemned'),
(172, 'confused'),
(173, 'conscious'),
(174, 'cooing'),
(175, 'cool'),
(176, 'cooperative'),
(177, 'coordinated'),
(178, 'courageous'),
(179, 'cowardly'),
(180, 'crabby'),
(181, 'craven'),
(182, 'crazy'),
(183, 'creepy'),
(184, 'crooked'),
(185, 'crowded'),
(186, 'cruel'),
(187, 'cuddly'),
(188, 'cultured'),
(189, 'cumbersome'),
(190, 'curious'),
(191, 'curly'),
(192, 'curved'),
(193, 'curvy'),
(194, 'cut'),
(195, 'cute'),
(196, 'cute'),
(197, 'cynical'),
(198, 'daffy'),
(199, 'daily'),
(200, 'damaged'),
(201, 'damaging'),
(202, 'damp'),
(203, 'dangerous'),
(204, 'dapper'),
(205, 'dark'),
(206, 'dashing'),
(207, 'dazzling'),
(208, 'dead'),
(209, 'deadpan'),
(210, 'deafening'),
(211, 'dear'),
(212, 'debonair'),
(213, 'decisive'),
(214, 'decorous'),
(215, 'deep'),
(216, 'deeply'),
(217, 'defeated'),
(218, 'defective'),
(219, 'defiant'),
(220, 'delicate'),
(221, 'delicious'),
(222, 'delightful'),
(223, 'delirious'),
(224, 'demonic'),
(225, 'dependent'),
(226, 'depressed'),
(227, 'deranged'),
(228, 'descriptive'),
(229, 'deserted'),
(230, 'detailed'),
(231, 'determined'),
(232, 'devilish'),
(233, 'didactic'),
(234, 'different'),
(235, 'difficult'),
(236, 'diligent'),
(237, 'direful'),
(238, 'dirty'),
(239, 'disagreeable'),
(240, 'disastrous'),
(241, 'discreet'),
(242, 'disgusted'),
(243, 'disgusting'),
(244, 'disillusioned'),
(245, 'dispensable'),
(246, 'distinct'),
(247, 'disturbed'),
(248, 'divergent'),
(249, 'dizzy'),
(250, 'domineering'),
(251, 'doubtful'),
(252, 'drab'),
(253, 'draconian'),
(254, 'dramatic'),
(255, 'dreary'),
(256, 'drunk'),
(257, 'dry'),
(258, 'dull'),
(259, 'dusty'),
(260, 'dusty'),
(261, 'dynamic'),
(262, 'dysfunctional'),
(263, 'eager'),
(264, 'early'),
(265, 'earsplitting'),
(266, 'earthy'),
(267, 'easy'),
(268, 'eatable'),
(269, 'economic'),
(270, 'educated'),
(271, 'efficacious'),
(272, 'efficient'),
(273, 'eight'),
(274, 'elastic'),
(275, 'elated'),
(276, 'elderly'),
(277, 'electric'),
(278, 'elegant'),
(279, 'elfin'),
(280, 'elite'),
(281, 'embarrassed'),
(282, 'eminent'),
(283, 'empty'),
(284, 'enchanted'),
(285, 'enchanting'),
(286, 'encouraging'),
(287, 'endurable'),
(288, 'energetic'),
(289, 'enormous'),
(290, 'entertaining'),
(291, 'enthusiastic'),
(292, 'envious'),
(293, 'equable'),
(294, 'equal'),
(295, 'erect'),
(296, 'erratic'),
(297, 'ethereal'),
(298, 'evanescent'),
(299, 'evasive'),
(300, 'even'),
(301, 'excellent'),
(302, 'excited'),
(303, 'exciting'),
(304, 'exclusive'),
(305, 'exotic'),
(306, 'expensive'),
(309, 'exuberant'),
(310, 'exultant'),
(311, 'fabulous'),
(312, 'faded'),
(313, 'faint'),
(314, 'fair'),
(315, 'faithful'),
(316, 'fallacious'),
(317, 'false'),
(318, 'familiar'),
(319, 'famous'),
(320, 'fanatical'),
(321, 'fancy'),
(322, 'fantastic'),
(323, 'far'),
(325, 'fascinated'),
(326, 'fast'),
(327, 'fat'),
(328, 'faulty'),
(329, 'fearful'),
(330, 'fearless'),
(331, 'feeble'),
(332, 'feigned'),
(333, 'female'),
(334, 'fertile'),
(335, 'festive'),
(336, 'few'),
(337, 'fierce'),
(338, 'filthy'),
(339, 'fine'),
(340, 'finicky'),
(341, 'first'),
(342, 'five'),
(343, 'fixed'),
(344, 'flagrant'),
(345, 'flaky'),
(346, 'flashy'),
(347, 'flat'),
(348, 'flawless'),
(349, 'flimsy'),
(350, 'flippant'),
(351, 'flowery'),
(352, 'fluffy'),
(353, 'fluttering'),
(354, 'foamy'),
(355, 'foolish'),
(356, 'foregoing'),
(357, 'forgetful'),
(358, 'fortunate'),
(359, 'four'),
(360, 'fragile'),
(361, 'frail'),
(362, 'frantic'),
(363, 'free'),
(364, 'freezing'),
(365, 'frequent'),
(366, 'fresh'),
(367, 'fretful'),
(368, 'friendly'),
(369, 'frightened'),
(370, 'frightening'),
(371, 'full'),
(372, 'fumbling'),
(373, 'functional'),
(374, 'funny'),
(375, 'furry'),
(376, 'furtive'),
(377, 'future'),
(378, 'futuristic'),
(379, 'fuzzy'),
(380, 'gabby'),
(381, 'gainful'),
(382, 'gamy'),
(383, 'gaping'),
(384, 'garrulous'),
(385, 'gaudy'),
(386, 'general'),
(387, 'gentle'),
(388, 'giant'),
(389, 'giddy'),
(390, 'gifted'),
(391, 'gigantic'),
(392, 'glamorous'),
(393, 'gleaming'),
(394, 'glib'),
(395, 'glistening'),
(396, 'glorious'),
(397, 'glossy'),
(398, 'godly'),
(399, 'good'),
(400, 'goofy'),
(401, 'gorgeous'),
(402, 'graceful'),
(403, 'grandiose'),
(404, 'grateful'),
(405, 'gratis'),
(406, 'greasy'),
(407, 'great'),
(408, 'greedy'),
(409, 'grieving'),
(410, 'groovy'),
(411, 'grotesque'),
(412, 'grouchy'),
(413, 'grubby'),
(414, 'gruesome'),
(415, 'grumpy'),
(416, 'guarded'),
(417, 'guiltless'),
(418, 'gullible'),
(419, 'gusty'),
(420, 'guttural'),
(421, 'habitual'),
(422, 'half'),
(423, 'hallowed'),
(424, 'halting'),
(425, 'handsome'),
(426, 'handsomely'),
(427, 'handy'),
(428, 'hanging'),
(429, 'hapless'),
(430, 'happy'),
(431, 'hard'),
(433, 'harmonious'),
(434, 'harsh'),
(435, 'hateful'),
(436, 'heady'),
(437, 'healthy'),
(438, 'heartbreaking'),
(439, 'heavenly'),
(440, 'heavy'),
(441, 'hellish'),
(442, 'helpful'),
(443, 'helpless'),
(444, 'hesitant'),
(445, 'hideous'),
(446, 'high'),
(447, 'highfalutin'),
(449, 'hilarious'),
(450, 'hissing'),
(451, 'historical'),
(452, 'holistic'),
(453, 'hollow'),
(454, 'homeless'),
(455, 'homely'),
(456, 'honorable'),
(457, 'horrible'),
(458, 'hospitable'),
(459, 'hot'),
(460, 'huge'),
(461, 'hulking'),
(462, 'humdrum'),
(463, 'humorous'),
(464, 'hungry'),
(465, 'hurried'),
(466, 'hurt'),
(467, 'hushed'),
(468, 'husky'),
(469, 'hypnotic'),
(470, 'hysterical'),
(471, 'icky'),
(472, 'icy'),
(473, 'idiotic'),
(474, 'ignorant'),
(475, 'ill'),
(476, 'illegal'),
(479, 'illustrious'),
(480, 'imaginary'),
(481, 'immense'),
(482, 'imminent'),
(483, 'impartial'),
(484, 'imperfect'),
(485, 'impolite'),
(486, 'important'),
(487, 'imported'),
(488, 'impossible'),
(489, 'incandescent'),
(490, 'incompetent'),
(491, 'inconclusive'),
(492, 'incredible'),
(493, 'industrious'),
(494, 'inexpensive'),
(495, 'infamous'),
(496, 'innate'),
(497, 'innocent'),
(498, 'inquisitive'),
(499, 'insidious'),
(500, 'instinctive'),
(501, 'intelligent'),
(502, 'interesting'),
(503, 'internal'),
(504, 'invincible'),
(505, 'irate'),
(506, 'irritating'),
(507, 'itchy'),
(508, 'jaded'),
(509, 'jagged'),
(510, 'jazzy'),
(511, 'jealous'),
(512, 'jittery'),
(513, 'jobless'),
(514, 'jolly'),
(515, 'joyous'),
(516, 'judicious'),
(517, 'juicy'),
(518, 'jumbled'),
(519, 'jumpy'),
(520, 'juvenile'),
(521, 'kaput'),
(522, 'keen'),
(523, 'kind'),
(524, 'kindhearted'),
(525, 'kindly'),
(526, 'knotty'),
(527, 'knowing'),
(528, 'knowledgeable'),
(529, 'known'),
(530, 'labored'),
(531, 'lackadaisical'),
(532, 'lacking'),
(533, 'lame'),
(534, 'lamentable'),
(535, 'languid'),
(536, 'large'),
(537, 'last'),
(538, 'late'),
(539, 'laughable'),
(540, 'lavish'),
(541, 'lazy'),
(542, 'lean'),
(543, 'learned'),
(544, 'left'),
(545, 'legal'),
(546, 'lethal'),
(547, 'level'),
(548, 'lewd'),
(549, 'light'),
(550, 'like'),
(551, 'likeable'),
(552, 'limping'),
(553, 'literate'),
(554, 'little'),
(555, 'lively'),
(556, 'lively'),
(557, 'living'),
(558, 'lonely'),
(559, 'long'),
(560, 'longing'),
(562, 'loose'),
(563, 'lopsided'),
(564, 'loud'),
(565, 'loutish'),
(566, 'lovely'),
(567, 'loving'),
(568, 'low'),
(569, 'lowly'),
(570, 'lucky'),
(571, 'ludicrous'),
(572, 'lumpy'),
(573, 'lush'),
(574, 'luxuriant'),
(575, 'lying'),
(576, 'lyrical'),
(577, 'macabre'),
(578, 'macho'),
(579, 'maddening'),
(580, 'madly'),
(581, 'magenta'),
(582, 'magical'),
(583, 'magnificent'),
(584, 'majestic'),
(585, 'makeshift'),
(586, 'male'),
(587, 'malicious'),
(588, 'mammoth'),
(589, 'maniacal'),
(590, 'many'),
(591, 'marked'),
(592, 'married'),
(593, 'marvelous'),
(594, 'massive'),
(595, 'material'),
(596, 'materialistic'),
(597, 'mature'),
(598, 'mean'),
(599, 'measly'),
(600, 'meaty'),
(601, 'medical'),
(602, 'meek'),
(603, 'mellow'),
(604, 'melodic'),
(605, 'melted'),
(606, 'merciful'),
(607, 'mere'),
(608, 'messy'),
(609, 'mighty'),
(610, 'military'),
(611, 'milky'),
(612, 'mindless'),
(613, 'miniature'),
(614, 'minor'),
(615, 'miscreant'),
(616, 'misty'),
(617, 'mixed'),
(618, 'moaning'),
(619, 'modern'),
(620, 'moldy'),
(621, 'momentous'),
(622, 'motionless'),
(623, 'mountainous'),
(624, 'muddled'),
(625, 'mundane'),
(626, 'murky'),
(627, 'mushy'),
(628, 'mute'),
(629, 'mysterious'),
(630, 'naive'),
(631, 'nappy'),
(632, 'narrow'),
(633, 'nasty'),
(634, 'natural'),
(635, 'naughty'),
(636, 'nauseating'),
(637, 'near'),
(638, 'neat'),
(639, 'nebulous'),
(640, 'necessary'),
(641, 'needless'),
(642, 'needy'),
(643, 'neighborly'),
(644, 'nervous'),
(645, 'new'),
(646, 'next'),
(647, 'nice'),
(648, 'nifty'),
(649, 'nimble'),
(650, 'nine'),
(651, 'nippy'),
(652, 'noiseless'),
(653, 'noisy'),
(654, 'nonchalant'),
(655, 'nondescript'),
(656, 'nonstop'),
(657, 'normal'),
(658, 'nostalgic'),
(659, 'nosy'),
(660, 'noxious'),
(661, 'null'),
(662, 'numberless'),
(663, 'numerous'),
(664, 'nutritious'),
(665, 'nutty'),
(666, 'oafish'),
(667, 'obedient'),
(668, 'obeisant'),
(669, 'obese'),
(670, 'obnoxious'),
(671, 'obscene'),
(672, 'obsequious'),
(673, 'observant'),
(674, 'obsolete'),
(675, 'obtainable'),
(676, 'oceanic'),
(677, 'odd'),
(678, 'offbeat'),
(679, 'old'),
(681, 'omniscient'),
(682, 'one'),
(683, 'onerous'),
(684, 'open'),
(685, 'opposite'),
(686, 'optimal'),
(687, 'ordinary'),
(688, 'organic'),
(689, 'ossified'),
(690, 'outgoing'),
(691, 'outrageous'),
(692, 'outstanding'),
(693, 'oval'),
(694, 'overconfident'),
(695, 'overjoyed'),
(696, 'overrated'),
(697, 'overt'),
(698, 'overwrought'),
(699, 'painful'),
(700, 'painstaking'),
(701, 'pale'),
(702, 'paltry'),
(703, 'panicky'),
(704, 'panoramic'),
(705, 'parallel'),
(706, 'parched'),
(707, 'parsimonious'),
(708, 'past'),
(709, 'pastoral'),
(710, 'pathetic'),
(711, 'peaceful'),
(712, 'penitent'),
(713, 'perfect'),
(714, 'periodic'),
(715, 'permissible'),
(716, 'perpetual'),
(717, 'petite'),
(718, 'petite'),
(719, 'phobic'),
(720, 'physical'),
(721, 'picayune'),
(722, 'pink'),
(723, 'piquant'),
(724, 'placid'),
(725, 'plain'),
(726, 'plant'),
(727, 'plastic'),
(728, 'plausible'),
(729, 'pleasant'),
(730, 'plucky'),
(731, 'pointless'),
(732, 'poised'),
(733, 'polite'),
(734, 'political'),
(735, 'poor'),
(736, 'possessive'),
(737, 'possible'),
(738, 'powerful'),
(739, 'precious'),
(740, 'premium'),
(741, 'present'),
(742, 'pretty'),
(743, 'previous'),
(744, 'pricey'),
(745, 'prickly'),
(746, 'private'),
(747, 'probable'),
(748, 'productive'),
(749, 'profuse'),
(750, 'protective'),
(751, 'proud'),
(752, 'psychedelic'),
(753, 'psychotic'),
(754, 'public'),
(755, 'puffy'),
(756, 'pumped'),
(757, 'puny'),
(758, 'purring'),
(759, 'pushy'),
(760, 'puzzled'),
(761, 'puzzling'),
(762, 'quack'),
(763, 'quaint'),
(764, 'quarrelsome'),
(765, 'questionable'),
(766, 'quick'),
(767, 'quickest'),
(768, 'quiet'),
(769, 'quirky'),
(770, 'quixotic'),
(771, 'quizzical'),
(772, 'rabid'),
(773, 'racial'),
(774, 'ragged'),
(775, 'rainy'),
(776, 'rambunctious'),
(777, 'rampant'),
(778, 'rapid'),
(779, 'rare'),
(780, 'raspy'),
(781, 'ratty'),
(782, 'ready'),
(783, 'real'),
(784, 'rebel'),
(785, 'receptive'),
(786, 'recondite'),
(787, 'redundant'),
(788, 'reflective'),
(789, 'regular'),
(790, 'relieved'),
(791, 'remarkable'),
(792, 'reminiscent'),
(793, 'repulsive'),
(794, 'resolute'),
(795, 'resonant'),
(796, 'responsible'),
(797, 'rhetorical'),
(798, 'rich'),
(799, 'right'),
(800, 'righteous'),
(801, 'rightful'),
(802, 'rigid'),
(803, 'ripe'),
(804, 'ritzy'),
(805, 'roasted'),
(806, 'robust'),
(807, 'romantic'),
(808, 'roomy'),
(809, 'rotten'),
(810, 'rough'),
(811, 'round'),
(812, 'royal'),
(813, 'ruddy'),
(814, 'rude'),
(815, 'rural'),
(816, 'rustic'),
(817, 'ruthless'),
(818, 'sable'),
(819, 'sad'),
(820, 'safe'),
(821, 'salty'),
(822, 'same'),
(823, 'sassy'),
(824, 'satisfying'),
(825, 'savory'),
(826, 'scandalous'),
(827, 'scarce'),
(828, 'scared'),
(829, 'scary'),
(830, 'scattered'),
(831, 'scientific'),
(832, 'scintillating'),
(833, 'scrawny'),
(834, 'screeching'),
(835, 'second'),
(837, 'secret'),
(838, 'secretive'),
(839, 'sedate'),
(840, 'seemly'),
(841, 'selective'),
(842, 'selfish'),
(843, 'separate'),
(844, 'serious'),
(845, 'shaggy'),
(846, 'shaky'),
(847, 'shallow'),
(848, 'sharp'),
(849, 'shiny'),
(850, 'shivering'),
(851, 'shocking'),
(852, 'short'),
(853, 'shrill'),
(854, 'shut'),
(855, 'shy'),
(856, 'sick'),
(857, 'silent'),
(858, 'silent'),
(859, 'silky'),
(860, 'silly'),
(861, 'simple'),
(862, 'simplistic'),
(863, 'sincere'),
(864, 'six'),
(865, 'skillful'),
(866, 'skinny'),
(867, 'sleepy'),
(868, 'slim'),
(869, 'slimy'),
(870, 'slippery'),
(871, 'sloppy'),
(872, 'slow'),
(873, 'small'),
(874, 'smart'),
(875, 'smelly'),
(876, 'smiling'),
(877, 'smoggy'),
(878, 'smooth'),
(879, 'sneaky'),
(880, 'snobbish'),
(881, 'snotty'),
(882, 'soft'),
(883, 'soggy'),
(884, 'solid'),
(885, 'somber'),
(886, 'sophisticated'),
(887, 'sordid'),
(888, 'sore'),
(889, 'sore'),
(890, 'sour'),
(891, 'sparkling'),
(892, 'special'),
(893, 'spectacular'),
(894, 'spicy'),
(895, 'spiffy'),
(896, 'spiky'),
(897, 'spiritual'),
(898, 'spiteful'),
(899, 'splendid'),
(900, 'spooky'),
(901, 'spotless'),
(902, 'spotted'),
(903, 'spotty'),
(904, 'spurious'),
(905, 'squalid'),
(906, 'square'),
(907, 'squealing'),
(908, 'squeamish'),
(909, 'staking'),
(910, 'stale'),
(911, 'standing'),
(912, 'statuesque'),
(913, 'steadfast'),
(914, 'steady'),
(915, 'steep'),
(916, 'stereotyped'),
(917, 'sticky'),
(918, 'stiff'),
(919, 'stimulating'),
(920, 'stingy'),
(921, 'stormy'),
(922, 'straight'),
(923, 'strange'),
(924, 'striped'),
(925, 'strong'),
(926, 'stupendous'),
(927, 'stupid'),
(928, 'sturdy'),
(929, 'subdued'),
(930, 'subsequent'),
(931, 'substantial'),
(932, 'successful'),
(933, 'succinct'),
(934, 'sudden'),
(935, 'sulky'),
(936, 'super'),
(937, 'superb'),
(938, 'superficial'),
(939, 'supreme'),
(940, 'swanky'),
(941, 'sweet'),
(942, 'sweltering'),
(943, 'swift'),
(944, 'symptomatic'),
(945, 'synonymous'),
(946, 'taboo'),
(947, 'tacit'),
(948, 'tacky'),
(949, 'talented'),
(950, 'tall'),
(951, 'tame'),
(952, 'tan'),
(953, 'tangible'),
(954, 'tangy'),
(955, 'tart'),
(956, 'tasteful'),
(957, 'tasteless'),
(958, 'tasty'),
(959, 'tawdry'),
(960, 'tearful'),
(961, 'tedious'),
(962, 'teeny'),
(964, 'telling'),
(965, 'temporary'),
(966, 'ten'),
(967, 'tender'),
(968, 'tense'),
(969, 'tense'),
(970, 'tenuous'),
(971, 'terrible'),
(972, 'terrific'),
(973, 'tested'),
(974, 'testy'),
(975, 'thankful'),
(976, 'therapeutic'),
(977, 'thick'),
(978, 'thin'),
(979, 'thinkable'),
(980, 'third'),
(981, 'thirsty'),
(982, 'thirsty'),
(983, 'thoughtful'),
(984, 'thoughtless'),
(985, 'threatening'),
(986, 'three'),
(987, 'thundering'),
(988, 'tidy'),
(989, 'tight'),
(990, 'tightfisted'),
(991, 'tiny'),
(992, 'tired'),
(993, 'tiresome'),
(994, 'toothsome'),
(995, 'torpid'),
(996, 'tough'),
(997, 'towering'),
(998, 'tranquil'),
(999, 'trashy'),
(1000, 'tremendous'),
(1001, 'tricky'),
(1002, 'trite'),
(1003, 'troubled'),
(1004, 'truculent'),
(1005, 'true'),
(1006, 'truthful'),
(1007, 'two'),
(1008, 'typical'),
(1009, 'ubiquitous'),
(1010, 'ugliest'),
(1011, 'ugly'),
(1012, 'ultra'),
(1013, 'unable'),
(1014, 'unaccountable'),
(1015, 'unadvised'),
(1016, 'unarmed'),
(1017, 'unbecoming'),
(1018, 'unbiased'),
(1019, 'uncovered'),
(1020, 'understood'),
(1021, 'undesirable'),
(1022, 'unequal'),
(1023, 'unequaled'),
(1024, 'uneven'),
(1025, 'unhealthy'),
(1026, 'uninterested'),
(1027, 'unique'),
(1028, 'unkempt'),
(1029, 'unknown'),
(1030, 'unnatural'),
(1031, 'unruly'),
(1032, 'unsightly'),
(1033, 'unsuitable'),
(1034, 'untidy'),
(1035, 'unused'),
(1036, 'unusual'),
(1037, 'unwieldy'),
(1038, 'unwritten'),
(1039, 'upbeat'),
(1040, 'uppity'),
(1041, 'upset'),
(1042, 'uptight'),
(1043, 'used'),
(1044, 'useful'),
(1045, 'useless'),
(1046, 'utopian'),
(1047, 'utter'),
(1048, 'uttermost'),
(1049, 'vacuous'),
(1050, 'vagabond'),
(1051, 'vague'),
(1052, 'valuable'),
(1053, 'various'),
(1054, 'vast'),
(1055, 'vengeful'),
(1056, 'venomous'),
(1057, 'verdant'),
(1058, 'versed'),
(1059, 'victorious'),
(1060, 'vigorous'),
(1061, 'violent'),
(1062, 'violet'),
(1063, 'vivacious'),
(1064, 'voiceless'),
(1065, 'volatile'),
(1066, 'voracious'),
(1067, 'vulgar'),
(1068, 'wacky'),
(1069, 'waggish'),
(1070, 'waiting'),
(1071, 'wakeful'),
(1072, 'wandering'),
(1073, 'wanting'),
(1074, 'warlike'),
(1075, 'warm'),
(1076, 'wary'),
(1077, 'wasteful'),
(1078, 'watery'),
(1079, 'weak'),
(1080, 'wealthy'),
(1081, 'weary'),
(1086, 'wet'),
(1087, 'whimsical'),
(1088, 'whispering'),
(1089, 'whole'),
(1090, 'wholesale'),
(1091, 'wicked'),
(1092, 'wide'),
(1094, 'wiggly'),
(1095, 'wild'),
(1096, 'willing'),
(1097, 'windy'),
(1098, 'wiry'),
(1099, 'wise'),
(1100, 'wistful'),
(1101, 'witty'),
(1102, 'woebegone'),
(1103, 'womanly'),
(1104, 'wonderful'),
(1105, 'wooden'),
(1106, 'woozy'),
(1107, 'workable'),
(1108, 'worried'),
(1109, 'worthless'),
(1110, 'wrathful'),
(1111, 'wretched'),
(1112, 'wrong'),
(1113, 'wry'),
(1114, 'xenogenic'),
(1115, 'xenolithic'),
(1116, 'xenophobic'),
(1117, 'xenotropic'),
(1118, 'xeric'),
(1119, 'xerographic'),
(1120, 'xerophilous'),
(1121, 'xerophthalmic'),
(1122, 'xerophytic'),
(1123, 'xerothermic'),
(1124, 'xerotic'),
(1125, 'xylographic'),
(1126, 'xyloid'),
(1127, 'xylophagous'),
(1128, 'xylophonic'),
(1129, 'yappy'),
(1130, 'yielding'),
(1131, 'yodeling'),
(1132, 'young'),
(1133, 'youthful'),
(1134, 'yucky'),
(1135, 'yummy'),
(1136, 'zany'),
(1137, 'zazzy'),
(1138, 'zealous'),
(1139, 'zesty'),
(1140, 'zippy'),
(1141, 'zoetic'),
(1142, 'zoic'),
(1143, 'zonked'),
(1144, 'zoological');

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

DROP TABLE IF EXISTS `animals`;
CREATE TABLE `animals` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `name`) VALUES
(1, 'aardvark'),
(2, 'addax'),
(3, 'albatross'),
(4, 'alligator'),
(5, 'alpaca'),
(6, 'ant'),
(7, 'anteater'),
(8, 'antelope'),
(9, 'aoudad'),
(10, 'ape'),
(11, 'argali'),
(12, 'armadillo'),
(321, 'quokka'),
(14, 'baboon'),
(15, 'badger'),
(16, 'barracuda'),
(17, 'basilisk'),
(18, 'bat'),
(19, 'bear'),
(20, 'beaver'),
(21, 'bee'),
(22, 'bighorn'),
(23, 'bird'),
(24, 'bison'),
(25, 'blue'),
(26, 'boar'),
(27, 'budgerigar'),
(28, 'buffalo'),
(29, 'bull'),
(30, 'bunny'),
(31, 'burro'),
(32, 'bush'),
(33, 'butterfly'),
(34, 'camel'),
(35, 'canary'),
(36, 'capybara'),
(37, 'caribou'),
(38, 'cat'),
(39, 'caterpillar'),
(40, 'chameleon'),
(41, 'chamois'),
(42, 'cheetah'),
(43, 'chicken'),
(44, 'chimpanzee'),
(45, 'chinchilla'),
(46, 'chipmunk'),
(47, 'chough'),
(48, 'civet'),
(49, 'clam'),
(50, 'coati'),
(51, 'cobra'),
(52, 'cockroach'),
(53, 'cod'),
(54, 'colt'),
(55, 'cony'),
(56, 'cormorant'),
(57, 'cougar'),
(58, 'cow'),
(59, 'coyote'),
(60, 'crab'),
(61, 'crane'),
(62, 'crocodile'),
(63, 'crow'),
(64, 'curlew'),
(65, 'deer'),
(66, 'dingo'),
(67, 'dinosaur'),
(68, 'doe'),
(69, 'dog'),
(70, 'dogfish'),
(71, 'dolphin'),
(72, 'donkey'),
(73, 'dormouse'),
(74, 'dotterel'),
(75, 'dove'),
(76, 'dragonfly'),
(77, 'dromedary'),
(78, 'duck'),
(79, 'duckbill'),
(80, 'dugong'),
(81, 'dunlin'),
(82, 'eagle'),
(83, 'echidna'),
(84, 'eel'),
(85, 'eland'),
(86, 'elephant'),
(87, 'elk'),
(88, 'emu'),
(89, 'ermine'),
(90, 'ewe'),
(91, 'falcon'),
(92, 'fawn'),
(93, 'ferret'),
(94, 'finch'),
(95, 'fish'),
(96, 'flamingo'),
(97, 'fly'),
(98, 'fox'),
(99, 'frog'),
(100, 'gaur'),
(101, 'gazelle'),
(102, 'gemsbok'),
(103, 'gerbil'),
(104, 'giant'),
(105, 'gila'),
(106, 'giraffe'),
(107, 'gnat'),
(108, 'gnu'),
(109, 'goat'),
(110, 'goldfinch'),
(111, 'goldfish'),
(112, 'goose'),
(113, 'gopher'),
(114, 'gorilla'),
(115, 'goshawk'),
(116, 'grasshopper'),
(117, 'grizzly'),
(118, 'ground'),
(119, 'grouse'),
(120, 'guanaco'),
(121, 'guinea'),
(122, 'gull'),
(123, 'hamster'),
(124, 'hare'),
(125, 'hartebeest'),
(126, 'hawk'),
(127, 'hedgehog'),
(128, 'heron'),
(129, 'herring'),
(130, 'hippopotamus'),
(131, 'hog'),
(132, 'hornet'),
(133, 'horse'),
(134, 'human'),
(135, 'hummingbird'),
(136, 'hyena'),
(137, 'ibex'),
(138, 'iguana'),
(139, 'impala'),
(140, 'jackal'),
(141, 'jaguar'),
(142, 'jay'),
(143, 'jellyfish'),
(144, 'jerboa'),
(145, 'kangaroo'),
(146, 'kid'),
(147, 'kinkajou'),
(148, 'kitten'),
(149, 'koala'),
(150, 'komodo'),
(151, 'koodoo'),
(152, 'kouprey'),
(153, 'kudu'),
(154, 'lamb'),
(155, 'lapwing'),
(156, 'lark'),
(157, 'lemur'),
(158, 'leopard'),
(159, 'lion'),
(160, 'lizard'),
(161, 'llama'),
(162, 'lobster'),
(163, 'locust'),
(164, 'loris'),
(165, 'louse'),
(166, 'lovebird'),
(167, 'lynx'),
(168, 'lyrebird'),
(169, 'magpie'),
(170, 'mallard'),
(171, 'manatee'),
(172, 'mandrill'),
(173, 'mare'),
(174, 'marmoset'),
(175, 'marten'),
(176, 'meerkat'),
(177, 'mink'),
(178, 'mole'),
(179, 'mongoose'),
(180, 'monkey'),
(181, 'monster'),
(182, 'moose'),
(183, 'mosquito'),
(184, 'mountain'),
(185, 'mouse'),
(186, 'mule'),
(187, 'musk'),
(189, 'muskrat'),
(190, 'mustang'),
(191, 'mynah'),
(192, 'narwhal'),
(193, 'newt'),
(194, 'nightingale'),
(195, 'ocelot'),
(196, 'octopus'),
(197, 'okapi'),
(198, 'opossum'),
(199, 'orangutan'),
(200, 'oryx'),
(201, 'ostrich'),
(202, 'otter'),
(203, 'owl'),
(204, 'ox'),
(205, 'oyster'),
(206, 'panda'),
(207, 'panther'),
(208, 'parakeet'),
(209, 'parrot'),
(210, 'partridge'),
(211, 'peafowl'),
(212, 'peccary'),
(213, 'pelican'),
(214, 'penguin'),
(215, 'pheasant'),
(216, 'pig'),
(217, 'pigeon'),
(218, 'platypus'),
(219, 'polar'),
(220, 'pony'),
(221, 'porcupine'),
(222, 'porpoise'),
(223, 'prairie'),
(224, 'pronghorn'),
(225, 'puma'),
(226, 'puppy'),
(227, 'quagga'),
(228, 'quail'),
(229, 'quelea'),
(230, 'rabbit'),
(231, 'raccoon'),
(232, 'rail'),
(233, 'ram'),
(234, 'rat'),
(235, 'raven'),
(236, 'red'),
(237, 'reindeer'),
(238, 'reptile'),
(239, 'rhinoceros'),
(240, 'roebuck'),
(241, 'rook'),
(242, 'ruff'),
(243, 'salamander'),
(244, 'salmon'),
(245, 'sand'),
(246, 'sandpiper'),
(247, 'sardine'),
(248, 'scorpion'),
(249, 'sea'),
(250, 'seahorse'),
(251, 'seal'),
(252, 'shark'),
(253, 'sheep'),
(254, 'shrew'),
(255, 'silver'),
(256, 'skunk'),
(257, 'sloth'),
(258, 'snail'),
(259, 'snake'),
(260, 'spider'),
(261, 'springbok'),
(262, 'squid'),
(263, 'squirrel'),
(264, 'stallion'),
(265, 'starling'),
(266, 'steer'),
(267, 'stingray'),
(268, 'stinkbug'),
(269, 'stork'),
(270, 'swallow'),
(271, 'swan'),
(272, 'tapir'),
(273, 'tarsier'),
(274, 'termite'),
(275, 'tiger'),
(276, 'toad'),
(277, 'trout'),
(278, 'turkey'),
(279, 'turtle'),
(280, 'uakari'),
(281, 'uguisu'),
(282, 'umbrellabird'),
(284, 'vicuña'),
(285, 'viper'),
(286, 'vulture'),
(287, 'wallaby'),
(288, 'walrus'),
(289, 'warthog'),
(290, 'wasp'),
(291, 'water'),
(292, 'waterbuck'),
(293, 'weasel'),
(294, 'whale'),
(295, 'wildcat'),
(296, 'wolf'),
(297, 'wolverine'),
(298, 'wombat'),
(299, 'woodchuck'),
(300, 'woodcock'),
(301, 'woodpecker'),
(302, 'worm'),
(303, 'wren'),
(304, 'xanclomys'),
(305, 'xanthareel'),
(306, 'xantus'),
(307, 'xeme'),
(308, 'xenarthra'),
(309, 'yaffle'),
(310, 'yak'),
(311, 'yakeil'),
(312, 'yaminon'),
(313, 'yapok'),
(314, 'yoldring'),
(315, 'yucker'),
(316, 'zander'),
(317, 'zebra'),
(318, 'zebu'),
(319, 'zeren'),
(320, 'zorilla');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
CREATE TABLE `badges` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `badges_players`
--

DROP TABLE IF EXISTS `badges_players`;
CREATE TABLE `badges_players` (
  `id` int(10) UNSIGNED NOT NULL,
  `badge_id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE `colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`) VALUES
(1, 'aero'),
(2, 'almond'),
(3, 'amaranth'),
(4, 'amazon'),
(5, 'amber'),
(6, 'amethyst'),
(7, 'antique'),
(8, 'apricot'),
(9, 'aqua'),
(10, 'aquamarine'),
(11, 'arsenic'),
(12, 'artichoke'),
(13, 'asparagus'),
(14, 'auburn'),
(15, 'aureolin'),
(16, 'avocado'),
(17, 'azure'),
(18, 'bazaar'),
(19, 'beaver'),
(20, 'beige'),
(21, 'bisque'),
(22, 'bistre'),
(23, 'bittersweet'),
(24, 'black'),
(25, 'blanched'),
(26, 'blond'),
(27, 'blue'),
(28, 'blueberry'),
(29, 'bluebonnet'),
(30, 'blush'),
(31, 'bole'),
(32, 'bone'),
(33, 'boysenberry'),
(34, 'brass'),
(35, 'bronze'),
(36, 'brown'),
(37, 'bubbles'),
(38, 'buff'),
(39, 'burgundy'),
(40, 'burlywood'),
(41, 'byzantine'),
(42, 'byzantium'),
(43, 'cadet'),
(44, 'camel'),
(45, 'capri'),
(46, 'cardinal'),
(47, 'carmine'),
(48, 'carnelian'),
(49, 'catawba'),
(50, 'ceil'),
(51, 'celadon'),
(52, 'celeste'),
(53, 'cerise'),
(54, 'cerulean'),
(55, 'chamoisee'),
(56, 'champagne'),
(57, 'charcoal'),
(58, 'chartreuse'),
(59, 'cherry'),
(60, 'chestnut'),
(61, 'chocolate'),
(62, 'cinereous'),
(63, 'cinnabar'),
(64, 'cinnamon'),
(65, 'citrine'),
(66, 'citron'),
(67, 'claret'),
(68, 'cobalt'),
(69, 'coconut'),
(70, 'coffee'),
(71, 'copper'),
(72, 'coquelicot'),
(73, 'coral'),
(74, 'cordovan'),
(75, 'corn'),
(76, 'cornflower'),
(77, 'cornsilk'),
(78, 'cream'),
(79, 'crimson'),
(80, 'cyan'),
(81, 'daffodil'),
(82, 'dandelion'),
(83, 'deer'),
(84, 'denim'),
(85, 'desert'),
(86, 'desire'),
(87, 'diamond'),
(88, 'dirt'),
(89, 'drab'),
(90, 'ebony'),
(91, 'ecru'),
(92, 'eggplant'),
(93, 'eggshell'),
(94, 'emerald'),
(95, 'eminence'),
(96, 'eucalyptus'),
(97, 'fallow'),
(98, 'fandango'),
(99, 'fawn'),
(100, 'feldgrau'),
(101, 'feldspar'),
(102, 'firebrick'),
(103, 'flame'),
(104, 'flattery'),
(105, 'flavescent'),
(106, 'flax'),
(107, 'flirt'),
(108, 'folly'),
(109, 'fuchsia'),
(110, 'fulvous'),
(111, 'gainsboro'),
(112, 'gamboge'),
(113, 'ginger'),
(114, 'glaucous'),
(115, 'glitter'),
(116, 'gold'),
(117, 'goldenrod'),
(118, 'grape'),
(119, 'gray'),
(120, 'green'),
(121, 'grullo'),
(122, 'harlequin'),
(123, 'heliotrope'),
(124, 'honeydew'),
(125, 'iceberg'),
(126, 'icterine'),
(127, 'imperial'),
(128, 'inchworm'),
(129, 'independence'),
(130, 'indigo'),
(131, 'iris'),
(132, 'irresistible'),
(133, 'isabelline'),
(134, 'ivory'),
(135, 'jade'),
(136, 'jasmine'),
(137, 'jasper'),
(138, 'jet'),
(139, 'jonquil'),
(140, 'keppel'),
(141, 'khaki'),
(142, 'kobe'),
(143, 'kobi'),
(144, 'lava'),
(145, 'lavender'),
(146, 'lemon'),
(147, 'liberty'),
(148, 'licorice'),
(149, 'lilac'),
(150, 'lime'),
(151, 'limerick'),
(152, 'linen'),
(153, 'lion'),
(154, 'liver'),
(155, 'livid'),
(156, 'lumber'),
(157, 'lust'),
(158, 'magenta'),
(159, 'magnolia'),
(160, 'mahogany'),
(161, 'maize'),
(162, 'malachite'),
(163, 'manatee'),
(164, 'mantis'),
(165, 'maroon'),
(166, 'mauve'),
(167, 'mauvelous'),
(168, 'melon'),
(169, 'midori'),
(170, 'mint'),
(171, 'moccasin'),
(172, 'mulberry'),
(173, 'mustard'),
(174, 'myrtle'),
(175, 'navy'),
(176, 'nyanza'),
(177, 'ochre'),
(178, 'olive'),
(179, 'olivine'),
(180, 'onyx'),
(181, 'orange'),
(182, 'orchid'),
(183, 'patriarch'),
(184, 'peach'),
(185, 'pear'),
(186, 'pearl'),
(187, 'peridot'),
(188, 'periwinkle'),
(189, 'persimmon'),
(190, 'peru'),
(191, 'phlox'),
(192, 'pink'),
(193, 'pistachio'),
(194, 'platinum'),
(195, 'plum'),
(196, 'popstar'),
(197, 'prune'),
(198, 'puce'),
(199, 'pumpkin'),
(200, 'purple'),
(201, 'purpureus'),
(202, 'quartz'),
(203, 'quencienta'),
(204, 'quercitron'),
(205, 'quicksand'),
(206, 'quince'),
(207, 'rackley'),
(208, 'rajah'),
(209, 'raspberry'),
(210, 'razzmatazz'),
(211, 'red'),
(212, 'redwood'),
(213, 'regalia'),
(214, 'rhythm'),
(215, 'rose'),
(216, 'rosewood'),
(217, 'ruber'),
(218, 'ruby'),
(219, 'ruddy'),
(220, 'rufous'),
(221, 'russet'),
(222, 'rust'),
(223, 'saffron'),
(224, 'salmon'),
(225, 'sand'),
(226, 'sandstorm'),
(227, 'sangria'),
(228, 'sapphire'),
(229, 'scarlet'),
(230, 'seashell'),
(231, 'sepia'),
(232, 'shadow'),
(233, 'shampoo'),
(234, 'sienna'),
(235, 'silver'),
(236, 'sinopia'),
(237, 'skobeloff'),
(238, 'slate'),
(239, 'smitten'),
(240, 'smoke'),
(241, 'snow'),
(242, 'soap'),
(243, 'stizza'),
(244, 'stormcloud'),
(245, 'straw'),
(246, 'strawberry'),
(247, 'sunglow'),
(248, 'sunray'),
(249, 'sunset'),
(250, 'tan'),
(251, 'tangelo'),
(252, 'tangerine'),
(253, 'taupe'),
(254, 'teal'),
(255, 'telemagenta'),
(256, 'thistle'),
(257, 'timberwolf'),
(258, 'tomato'),
(259, 'toolbox'),
(260, 'topaz'),
(261, 'tulip'),
(262, 'tumbleweed'),
(263, 'turquoise'),
(264, 'tuscan'),
(265, 'tuscany'),
(266, 'ube'),
(267, 'ultramarine'),
(268, 'ultraviolet'),
(269, 'umber'),
(270, 'urobilin'),
(271, 'vanilla'),
(272, 'verdigris'),
(273, 'vermilion'),
(274, 'veronica'),
(275, 'violet'),
(276, 'viridian'),
(277, 'waterspout'),
(278, 'wenge'),
(279, 'wheat'),
(280, 'white'),
(281, 'wine'),
(282, 'wisteria'),
(283, 'xanadu'),
(284, 'xanthic'),
(285, 'yellow'),
(286, 'zaffre'),
(287, 'zomp'),
(288, 'zucchini');

-- --------------------------------------------------------

--
-- Table structure for table `forgots`
--

DROP TABLE IF EXISTS `forgots`;
CREATE TABLE `forgots` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` char(32) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(10) UNSIGNED NOT NULL,
  `game_type_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game_types`
--

DROP TABLE IF EXISTS `game_types`;
CREATE TABLE `game_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `max_team_size` tinyint(2) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'User'),
(3, 'Guest');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `id` int(10) UNSIGNED NOT NULL,
  `tournament_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `quality` decimal(6,4) NOT NULL DEFAULT '0.0000',
  `created` datetime NOT NULL,
  `winning_team_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `matches_teams`
--

DROP TABLE IF EXISTS `matches_teams`;
CREATE TABLE `matches_teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `match_id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `copy` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `players_teams`
--

DROP TABLE IF EXISTS `players_teams`;
CREATE TABLE `players_teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player_rankings`
--

DROP TABLE IF EXISTS `player_rankings`;
CREATE TABLE `player_rankings` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `game_type_id` int(10) UNSIGNED NOT NULL,
  `mean` decimal(15,12) DEFAULT NULL,
  `std_deviation` decimal(15,12) DEFAULT NULL,
  `games_played` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `max_mean` decimal(15,12) DEFAULT '25.000000000000',
  `min_mean` decimal(15,12) DEFAULT '25.000000000000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player_stats`
--

DROP TABLE IF EXISTS `player_stats`;
CREATE TABLE `player_stats` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `game_id` int(10) UNSIGNED NOT NULL,
  `wins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `draws` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `losses` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `streak` smallint(6) NOT NULL DEFAULT '0',
  `global_wins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `global_draws` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `global_losses` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `max_streak` smallint(6) NOT NULL DEFAULT '0',
  `min_streak` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rank_history`
--

DROP TABLE IF EXISTS `rank_history`;
CREATE TABLE `rank_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_ranking_id` int(10) UNSIGNED NOT NULL,
  `mean` decimal(15,12) NOT NULL,
  `std_deviation` decimal(15,12) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `default` text,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sitting_outs`
--

DROP TABLE IF EXISTS `sitting_outs`;
CREATE TABLE `sitting_outs` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `tournament_id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

DROP TABLE IF EXISTS `songs`;
CREATE TABLE `songs` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_dir` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `played` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `tournament_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_seed` smallint(2) DEFAULT NULL,
  `seed` smallint(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE `tournaments` (
  `id` int(10) UNSIGNED NOT NULL,
  `game_id` int(10) UNSIGNED NOT NULL,
  `tournament_type` varchar(255) NOT NULL,
  `team_size` tinyint(2) UNSIGNED NOT NULL,
  `ranked` tinyint(1) NOT NULL DEFAULT '1',
  `quality` decimal(6,4) NOT NULL DEFAULT '0.0000',
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '2',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ident` char(32) DEFAULT NULL,
  `token` char(32) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `player_id` int(10) UNSIGNED NOT NULL,
  `song_id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjectives`
--
ALTER TABLE `adjectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges_players`
--
ALTER TABLE `badges_players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `badge_id` (`badge_id`,`player_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forgots`
--
ALTER TABLE `forgots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_type_id` (`game_type_id`);

--
-- Indexes for table `game_types`
--
ALTER TABLE `game_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `winning_team_id` (`winning_team_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `matches_teams`
--
ALTER TABLE `matches_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `match_id` (`match_id`,`team_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`,`active`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `players_teams`
--
ALTER TABLE `players_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `player_id` (`player_id`,`team_id`);

--
-- Indexes for table `player_rankings`
--
ALTER TABLE `player_rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `player_id` (`player_id`,`game_type_id`),
  ADD KEY `max_mean` (`max_mean`),
  ADD KEY `min_mean` (`min_mean`);

--
-- Indexes for table `player_stats`
--
ALTER TABLE `player_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `player_id` (`player_id`,`game_id`),
  ADD KEY `max_streak` (`max_streak`),
  ADD KEY `min_streak` (`min_streak`);

--
-- Indexes for table `rank_history`
--
ALTER TABLE `rank_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sitting_outs`
--
ALTER TABLE `sitting_outs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`,`created`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `played` (`played`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `seed` (`seed`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `active` (`active`),
  ADD KEY `ident` (`ident`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `player_id` (`player_id`,`song_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjectives`
--
ALTER TABLE `adjectives`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1145;

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `badges_players`
--
ALTER TABLE `badges_players`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `forgots`
--
ALTER TABLE `forgots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `game_types`
--
ALTER TABLE `game_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches_teams`
--
ALTER TABLE `matches_teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `players_teams`
--
ALTER TABLE `players_teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `player_rankings`
--
ALTER TABLE `player_rankings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `player_stats`
--
ALTER TABLE `player_stats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rank_history`
--
ALTER TABLE `rank_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitting_outs`
--
ALTER TABLE `sitting_outs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
