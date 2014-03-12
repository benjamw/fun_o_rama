-- --------------------------------------------------------

--
-- Table structure for table `sitting_outs`
--

DROP TABLE IF EXISTS `sitting_outs`;
CREATE TABLE IF NOT EXISTS `sitting_outs` (
`id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `tournament_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sitting_outs`
--
ALTER TABLE `sitting_outs`
 ADD PRIMARY KEY (`id`), ADD KEY `player_id` (`player_id`,`created`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sitting_outs`
--
ALTER TABLE `sitting_outs`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;


