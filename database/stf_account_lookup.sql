USE [EHSINFO]
GO

/****** Object:  StoredProcedure [dbo].[stf_account_lookup]    Script Date: 5/22/2017 8:31:20 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


-- Create date: 2015-07-27
-- Description:	Get single account detail by account. No subtable info.
-- =============================================

CREATE PROCEDURE [dbo].[stf_account_lookup]
	
	-- filter
	@account			varchar(10)		= NULL	
	
	
AS	
	SET NOCOUNT ON;
	
	-- Set defaults.
		-- Filters.		
		
		-- Sorting field.		
		
		-- Sorting order.		
		
	-- Set up table var so we can reuse results.		
	CREATE TABLE #cache_primary
	(
		account		varchar(10),
		department	varchar(5),
		details		varchar(max),
		name_f		varchar(25),
		name_l		varchar(25),
		name_m		varchar(25),
		status		int,
		row_id		int,
		id			int,
		log_update	datetime2
	)		
		
	-- Populate main table var. This is the primary query. Order
	-- and query details go here.
	INSERT INTO #cache_primary (id, account, department, details, status, name_f, name_l, name_m)
		SELECT
				_main.id, 
				_main.account, 
				_main.department,
				_main.details,
				_main.status,
				_main.name_f,
				_main.name_l,
				_main.name_m	
		FROM dbo.tbl_staff _main
		WHERE _main.account = @account
		
	
	-- Main detail	
	SELECT	
		* 
	FROM 
		#cache_primary _data
	 
	
	

GO


