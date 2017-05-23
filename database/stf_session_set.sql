USE [EHSINFO]
GO

/****** Object:  StoredProcedure [dbo].[stf_session_set]    Script Date: 5/22/2017 8:33:58 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


-- Create date: 2015-05-22
-- Description:	Write session data.
-- =============================================
CREATE PROCEDURE [dbo].[stf_session_set]
	
	-- Parameters
	@session_id		varchar(26)		= NULL,	-- Primary key.
	@data			varchar(max)	= NULL,
	@source			varchar(2048)	= NULL,
	@host			varchar(50)		= NULL

AS	
	
BEGIN
	
	SET NOCOUNT ON;	 
	
		MERGE INTO dbo.tbl_stf_session
		USING 
				(SELECT @session_id AS session_id) AS _search
			ON 
				tbl_stf_session.session_id = _search.session_id
			
			WHEN MATCHED THEN
				UPDATE SET
					session_data	= @data,
					last_update		= GETDATE(),
					source			= @source,
					host			= @host
			
			WHEN NOT MATCHED THEN
				INSERT (session_id, 
						session_data, 
						last_update, 
						source, 
						host)
						
				VALUES (_search.session_id, 
						@data, 
						GETDATE(), 
						@source, 
						@host);
					
END


GO


