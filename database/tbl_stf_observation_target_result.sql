USE [EHSINFO]
GO

/****** Object:  Table [dbo].[tbl_stf_observation_target_result]    Script Date: 5/23/2017 8:56:00 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_stf_observation_target_result](
	[id_key] [int] IDENTITY(1,1) NOT NULL,
	[fk_id] [int] NULL,
	[item] [int] NULL,
	[result] [bit] NULL,
 CONSTRAINT [PK_tbl_stf_observation_target_result] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

ALTER TABLE [dbo].[tbl_stf_observation_target_result]  WITH CHECK ADD  CONSTRAINT [FK_tbl_stf_observation_target_result_tbl_stf_observation_target] FOREIGN KEY([fk_id])
REFERENCES [dbo].[tbl_stf_observation_target] ([id_key])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[tbl_stf_observation_target_result] CHECK CONSTRAINT [FK_tbl_stf_observation_target_result_tbl_stf_observation_target]
GO


