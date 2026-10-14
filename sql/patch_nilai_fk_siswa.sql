ALTER TABLE `nilai` DROP FOREIGN KEY `nilai_ibfk_1`;

ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);
