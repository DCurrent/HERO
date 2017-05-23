USE [EHSINFO]
GO

/****** Object:  StoredProcedure [dbo].[stf_session_clean]    Script Date: 5/22/2017 8:32:35 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


-- Create date: 2015-05-23
-- Description:	Remove all expired session data.
-- =============================================
CREATE PROCEDURE [dbo].[stf_session_clean]
	
	-- Parameters
	@life_max	int = 1440	-- Maximum lifetime of a session in seconds.

AS	
BEGIN
	
	SET NOCOUNT ON;	 
	
		DELETE FROM dbo.tbl_stf_session WHERE (DATEDIFF(SECOND, last_update, GETDATE()) > @life_max)
					
END


GO


