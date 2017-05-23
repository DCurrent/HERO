USE [EHSINFO]
GO

/****** Object:  Table [dbo].[tbl_stf_master]    Script Date: 5/22/2017 8:35:05 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_stf_master](
	[id_key] [int] IDENTITY(1,1) NOT NULL,
	[id] [int] NOT NULL,
	[active] [bit] NOT NULL,
	[create_by] [int] NOT NULL,
	[create_host] [varchar](50) NOT NULL,
	[create_time] [datetime2](7) NOT NULL,
	[create_etime]  AS (datediff(second,[create_time],getdate())),
	[update_by] [int] NOT NULL,
	[update_host] [varchar](50) NOT NULL,
	[update_time] [datetime2](7) NOT NULL,
	[update_etime]  AS (datediff(second,[update_time],getdate())),
	[temp_id] [uniqueidentifier] NULL,
 CONSTRAINT [PK_tbl_stf_master] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_id_group]  DEFAULT ((-1)) FOR [id]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_active]  DEFAULT ((1)) FOR [active]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_create_by]  DEFAULT ((-1)) FOR [create_by]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_create_host]  DEFAULT (host_name()) FOR [create_host]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_Table_1_log_created]  DEFAULT (getdate()) FOR [create_time]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_update_by]  DEFAULT ((-1)) FOR [update_by]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_update_host]  DEFAULT (host_name()) FOR [update_host]
GO

ALTER TABLE [dbo].[tbl_stf_master] ADD  CONSTRAINT [DF_tbl_stf_master_update_time]  DEFAULT (getdate()) FOR [update_time]
GO


