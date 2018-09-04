USE [EHSINFO]
GO

/****** Object:  Table [dbo].[tbl_stf_observation_target]    Script Date: 2018-09-04 11:09:39 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_stf_observation_target](
	[id_key] [int] NOT NULL,
	[label] [varchar](50) NULL,
	[details] [varchar](max) NULL,
	[building_code] [char](4) NULL,
	[room_code] [char](6) NULL,
	[address] [varchar](max) NULL,
 CONSTRAINT [PK_tbl_stf_observation_target] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO

ALTER TABLE [dbo].[tbl_stf_observation_target]  WITH CHECK ADD  CONSTRAINT [FK_tbl_stf_observation_target_tbl_stf_master] FOREIGN KEY([id_key])
REFERENCES [dbo].[tbl_stf_master] ([id_key])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[tbl_stf_observation_target] CHECK CONSTRAINT [FK_tbl_stf_observation_target_tbl_stf_master]
GO


