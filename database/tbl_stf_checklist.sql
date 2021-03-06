USE [EHSINFO]
GO

/****** Object:  Table [dbo].[tbl_stf_checklist]    Script Date: 5/22/2017 8:34:39 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_stf_checklist](
	[id_key] [int] NOT NULL,
	[label] [varchar](50) NULL,
	[details] [varchar](max) NULL,
 CONSTRAINT [PK_tbl_stf_checklist] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO

ALTER TABLE [dbo].[tbl_stf_checklist]  WITH CHECK ADD  CONSTRAINT [FK_tbl_stf_master] FOREIGN KEY([id_key])
REFERENCES [dbo].[tbl_stf_master] ([id_key])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[tbl_stf_checklist] CHECK CONSTRAINT [FK_tbl_stf_master]
GO


