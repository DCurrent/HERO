USE [EHSINFO]
GO

/****** Object:  StoredProcedure [dbo].[stf_session_destroy]    Script Date: 5/22/2017 8:33:00 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


-- Create date: 2015-05-23
-- Description:	Destroy session data.
-- =============================================
CREATE PROCEDURE [dbo].[stf_session_destroy]
	
	-- Parameters
	@id				varchar(40) = NULL	-- Primary key.

AS	
BEGIN
	
	SET NOCOUNT ON;	 
	
		DELETE FROM dbo.tbl_stf_session WHERE session_id = @id
					
END


GO


